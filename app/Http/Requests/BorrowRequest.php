<?php

namespace App\Http\Requests;

use App\Models\Device;
use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class BorrowRequest extends FormRequest
{
    /** Số ngày mượn tối đa cho 1 phiếu. */
    public const MAX_DAYS = 14;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'device_id' => ['required', 'integer', 'exists:devices,id,deleted_at,NULL'],
            'borrow_date' => ['required', 'date', 'after_or_equal:today'],
            'return_date' => ['required', 'date', 'after_or_equal:borrow_date'],
            'purpose' => ['required', 'string', 'min:5', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'device_id.required' => 'Vui lòng chọn thiết bị muốn mượn.',
            'device_id.exists' => 'Thiết bị không tồn tại hoặc đã ngừng sử dụng.',
            'borrow_date.required' => 'Vui lòng chọn ngày mượn.',
            'borrow_date.date' => 'Ngày mượn không hợp lệ.',
            'borrow_date.after_or_equal' => 'Ngày mượn không được ở trong quá khứ.',
            'return_date.required' => 'Vui lòng chọn ngày trả.',
            'return_date.date' => 'Ngày trả không hợp lệ.',
            'return_date.after_or_equal' => 'Ngày trả phải lớn hơn hoặc bằng ngày mượn.',
            'purpose.required' => 'Vui lòng nhập mục đích sử dụng.',
            'purpose.min' => 'Mục đích sử dụng phải có ít nhất :min ký tự.',
            'purpose.max' => 'Mục đích sử dụng không được vượt quá :max ký tự.',
        ];
    }

    /**
     * Kiểm tra nghiệp vụ sau khi các rule cơ bản đã đạt:
     *  - thiết bị phải đang "Sẵn sàng"
     *  - không mượn quá MAX_DAYS ngày
     *  - người dùng chưa có phiếu chờ duyệt/đang mượn cho cùng thiết bị
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $device = Device::find($this->integer('device_id'));
                if (! $device?->isAvailable()) {
                    $validator->errors()->add('device_id', 'Thiết bị đang bận (đang được mượn hoặc bảo trì), vui lòng chọn thiết bị khác.');

                    return;
                }

                $days = $this->date('borrow_date')->diffInDays($this->date('return_date'));
                if ($days > self::MAX_DAYS) {
                    $validator->errors()->add('return_date', 'Chỉ được mượn tối đa '.self::MAX_DAYS.' ngày cho mỗi lần.');
                }

                $duplicated = Transaction::where('user_id', $this->user()->id)
                    ->where('device_id', $device->id)
                    ->whereIn('status', [Transaction::STATUS_PENDING, Transaction::STATUS_APPROVED])
                    ->exists();
                if ($duplicated) {
                    $validator->errors()->add('device_id', 'Bạn đã có yêu cầu đang chờ duyệt cho thiết bị này.');
                }
            },
        ];
    }
}
