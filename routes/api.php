<?php

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
| API chỉ đọc (JSON) – danh sách thiết bị có tìm kiếm + phân trang.
| Ví dụ: GET /api/devices?keyword=laptop&category_id=1&status=available&page=2
*/
Route::get('/devices', function (Request $request) {
    return Device::with('category:id,name')
        ->filter($request->only(['keyword', 'category_id', 'status']))
        ->orderBy('name')
        ->paginate(12, ['id', 'category_id', 'code', 'name', 'status', 'image'])
        ->withQueryString();
})->middleware('throttle:60,1');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
