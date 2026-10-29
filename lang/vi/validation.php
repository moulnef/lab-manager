<?php

/*
| Thông báo kiểm tra dữ liệu mặc định bằng tiếng Việt.
| Các Form Request (CategoryRequest, DeviceRequest, BorrowRequest) có thông báo riêng chi tiết hơn.
*/
return [
    'accepted' => ':attribute phải được chấp nhận.',
    'after_or_equal' => ':attribute phải là ngày sau hoặc bằng :date.',
    'boolean' => ':attribute chỉ nhận giá trị đúng hoặc sai.',
    'confirmed' => 'Xác nhận :attribute không khớp.',
    'date' => ':attribute không phải là ngày hợp lệ.',
    'email' => ':attribute phải là địa chỉ email hợp lệ.',
    'exists' => ':attribute đã chọn không hợp lệ.',
    'file' => ':attribute phải là một tệp tin.',
    'image' => ':attribute phải là hình ảnh.',
    'in' => ':attribute đã chọn không hợp lệ.',
    'integer' => ':attribute phải là số nguyên.',
    'max' => [
        'file' => ':attribute không được lớn hơn :max KB.',
        'numeric' => ':attribute không được lớn hơn :max.',
        'string' => ':attribute không được vượt quá :max ký tự.',
        'array' => ':attribute không được có quá :max phần tử.',
    ],
    'mimes' => ':attribute phải có định dạng: :values.',
    'min' => [
        'file' => ':attribute phải có dung lượng tối thiểu :min KB.',
        'numeric' => ':attribute phải lớn hơn hoặc bằng :min.',
        'string' => ':attribute phải có ít nhất :min ký tự.',
        'array' => ':attribute phải có ít nhất :min phần tử.',
    ],
    'numeric' => ':attribute phải là số.',
    'regex' => ':attribute không đúng định dạng.',
    'required' => 'Vui lòng nhập :attribute.',
    'string' => ':attribute phải là chuỗi ký tự.',
    'unique' => ':attribute đã tồn tại.',
    'uploaded' => ':attribute tải lên thất bại.',

    'attributes' => [
        'name' => 'tên',
        'email' => 'email',
        'password' => 'mật khẩu',
        'code' => 'mã thiết bị',
        'category_id' => 'danh mục',
        'status' => 'trạng thái',
        'image' => 'ảnh',
        'description' => 'mô tả',
        'borrow_date' => 'ngày mượn',
        'return_date' => 'ngày trả',
        'purpose' => 'mục đích sử dụng',
        'admin_note' => 'lý do',
    ],
];
