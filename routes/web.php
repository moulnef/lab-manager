<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\DeviceController;
use App\Models\Device;

Route::get('/', function () {
    return view('welcome');
});

// Nhóm Route dành riêng cho Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
    Route::get('/devices/create', [DeviceController::class, 'create'])->name('devices.create');
    Route::post('/devices', [DeviceController::class, 'store'])->name('devices.store');
});

// ===== Phần User (Việt) =====
Route::get('/devices', function () {
    return view('devices.index', ['devices' => Device::all()]);
})->name('user.devices');

Route::get('/borrow/create', function (Request $request) {
    return view('borrow.create', [
        'devices' => Device::all(),
        'selectedId' => $request->query('device'),
    ]);
})->name('user.borrow.create');
