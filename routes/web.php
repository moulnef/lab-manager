<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceCatalogController;
use App\Http\Controllers\BorrowController;

// Nhóm Route dành riêng cho Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
    Route::get('/devices/create', [DeviceController::class, 'create'])->name('devices.create');
    Route::post('/devices', [DeviceController::class, 'store'])->name('devices.store');
});

// ===== Phần User (Việt) =====
Route::get('/', [DeviceCatalogController::class, 'home'])->name('home');
Route::get('/devices', [DeviceCatalogController::class, 'index'])->name('user.devices');
Route::get('/devices/{device}', [DeviceCatalogController::class, 'show'])->name('user.devices.show');
Route::post('/borrow', [BorrowController::class, 'store'])->name('user.borrow.store');
Route::get('/my-borrows', [BorrowController::class, 'index'])->name('user.borrows');
Route::delete('/my-borrows/{transaction}', [BorrowController::class, 'cancel'])->name('user.borrows.cancel');
