<?php

namespace App\Http\Requests;

use App\Models\Device;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    protected function prepareForValidation(): void
    {
        // Chuẩn hóa mã thiết bị: bỏ khoảng trắng, viết hoa
        if ($this->filled('code')) {
            $this->merge(['code' => strtoupper(trim($this->input('code')))]);
        }
    }

    public function rules(): array
    {
        $deviceId = $this->route('device')?->id;

        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'code' => [
                'required', 'string', 'max:30', 'regex:/^[A-Z0-9\-]+$/',
                Rule::unique('devices', 'code')->ignore($deviceId), // kiểm tra cả bản ghi đã xóa mềm
            ],
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', Rule::in(array_keys(Device::STATUSES))],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục đã chọn không tồn tại.',
            'code.required' => 'Vui lòng nhập mã thiết bị.',
            'code.regex' => 'Mã thiết bị chỉ gồm chữ in hoa, số và dấu gạch ngang (vd: LAB-LT-010).',
            'code.unique' => 'Mã thiết bị này đã được sử dụng.',
            'code.max' => 'Mã thiết bị không được vượt quá :max ký tự.',
            'name.required' => 'Vui lòng nhập tên thiết bị.',
            'name.min' => 'Tên thiết bị phải có ít nhất :min ký tự.',
            'name.max' => 'Tên thiết bị không được vượt quá :max ký tự.',
            'description.max' => 'Mô tả không được vượt quá :max ký tự.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Ảnh chỉ chấp nhận định dạng: jpg, jpeg, png, webp.',
            'image.max' => 'Ảnh không được lớn hơn 2MB.',
            'image.uploaded' => 'Tải ảnh lên thất bại (có thể ảnh quá lớn).',
        ];
    }
}
