<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Models\Device;

// Trang chủ chuyển hướng đến trang danh sách thiết bị
Route::get('/', function () {
    return redirect()->route('user.devices');
});

// ==========================================
// 1. CÁC ROUTE XÁC THỰC (AUTHENTICATION)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==========================================
// 2. NHÓM ROUTE QUẢN TRỊ VIÊN (ADMIN ONLY)
// Bảo vệ bằng Middleware Auth & Admin (Chỉ role 'admin' mới được vào)
// ==========================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard & Thống kê
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard.index');

    // Quản lý thiết bị (CRUD RESTful)
    Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
    Route::get('/devices/create', [DeviceController::class, 'create'])->name('devices.create');
    Route::post('/devices', [DeviceController::class, 'store'])->name('devices.store');
    Route::get('/devices/{device}/edit', [DeviceController::class, 'edit'])->name('devices.edit');
    Route::put('/devices/{device}', [DeviceController::class, 'update'])->name('devices.update');
    Route::delete('/devices/{device}', [DeviceController::class, 'destroy'])->name('devices.destroy');
});

// ==========================================
// 3. PHẦN GIAO DIỆN USER (VIỆT)
// ==========================================
Route::get('/devices', function () {
    return view('devices.index', ['devices' => Device::all()]);
})->name('user.devices');

Route::get('/borrow/create', function (Request $request) {
    return view('borrow.create', [
        'devices' => Device::all(),
        'selectedId' => $request->query('device'),
    ]);
})->name('user.borrow.create');
