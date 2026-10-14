<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;

Route::get('/devices', [DeviceController::class, 'getDevices']);
Route::post('/devices/borrow', [DeviceController::class, 'borrow']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
