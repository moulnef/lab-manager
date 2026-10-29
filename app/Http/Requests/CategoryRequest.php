<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')?->id;

        return [
            'name' => ['required', 'string', 'min:3', 'max:100', Rule::unique('categories', 'name')->ignore($categoryId)],
            'icon' => ['nullable', 'string', 'max:50', 'regex:/^[a-z0-9\-]+$/'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.min' => 'Tên danh mục phải có ít nhất :min ký tự.',
            'name.max' => 'Tên danh mục không được vượt quá :max ký tự.',
            'name.unique' => 'Tên danh mục này đã tồn tại.',
            'icon.regex' => 'Tên icon chỉ gồm chữ thường, số và dấu gạch ngang (vd: laptop, cpu).',
            'icon.max' => 'Tên icon không được vượt quá :max ký tự.',
            'description.max' => 'Mô tả không được vượt quá :max ký tự.',
        ];
    }
}
