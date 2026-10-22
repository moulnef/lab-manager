<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BorrowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_id' => ['required', 'exists:devices,id'],
            'borrow_date' => ['required', 'date', 'after_or_equal:today'],
            'return_date' => ['required', 'date', 'after_or_equal:borrow_date'],
        ];
    }

    public function messages(): array
    {
        return [
            'device_id.required' => 'Vui lòng chọn thiết bị.',
            'device_id.exists' => 'Thiết bị không tồn tại.',
            'borrow_date.required' => 'Vui lòng chọn ngày mượn.',
            'borrow_date.after_or_equal' => 'Ngày mượn không được trước hôm nay.',
            'return_date.required' => 'Vui lòng chọn ngày trả.',
            'return_date.after_or_equal' => 'Ngày trả phải từ ngày mượn trở đi.',
        ];
    }
}
