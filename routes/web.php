<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;

Route::get('/', function () {
    return view('welcome');
});

// Nhóm Route dành riêng cho Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
    Route::get('/devices/create', [DeviceController::class, 'create'])->name('devices.create');
    Route::post('/devices', [DeviceController::class, 'store'])->name('devices.store');
});