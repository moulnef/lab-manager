<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeviceController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\DeviceCatalogController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. KHU VỰC CÔNG KHAI (khách và người dùng đều xem được)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/devices', [DeviceCatalogController::class, 'index'])->name('devices.index');
Route::get('/devices/{device}', [DeviceCatalogController::class, 'show'])
    ->whereNumber('device')
    ->name('devices.show');

/*
|--------------------------------------------------------------------------
| 2. XÁC THỰC (Laravel Auth – session guard "web")
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| 3. NGƯỜI DÙNG ĐÃ ĐĂNG NHẬP – mượn thiết bị
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user,admin'])->group(function () {
    Route::get('/borrow/create', [BorrowController::class, 'create'])->name('borrow.create');
    Route::post('/borrow', [BorrowController::class, 'store'])->name('borrow.store');
    Route::get('/my-borrows', [BorrowController::class, 'history'])->name('borrow.history');
    Route::patch('/my-borrows/{transaction}/cancel', [BorrowController::class, 'cancel'])->name('borrow.cancel');
});

/*
|--------------------------------------------------------------------------
| 4. KHU VỰC QUẢN TRỊ /admin/* – chỉ tài khoản role = admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/', fn () => redirect()->route('admin.dashboard'));
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Bảng 1: Danh mục
        Route::resource('categories', CategoryController::class);

        // Bảng 2: Thiết bị (+ thùng rác / khôi phục xóa mềm)
        Route::get('devices/trash', [DeviceController::class, 'trash'])->name('devices.trash');
        Route::patch('devices/{id}/restore', [DeviceController::class, 'restore'])
            ->whereNumber('id')
            ->name('devices.restore');
        Route::resource('devices', DeviceController::class);

        // Phiếu mượn
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::patch('transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
        Route::patch('transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');
        Route::patch('transactions/{transaction}/return', [TransactionController::class, 'markReturned'])->name('transactions.return');
    });
