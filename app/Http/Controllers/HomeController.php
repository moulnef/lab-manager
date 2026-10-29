<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Device;

class HomeController extends Controller
{
    /** Trang chủ: giới thiệu nhanh, danh mục và thiết bị mới. */
    public function index()
    {
        $categories = Category::withCount('devices')->orderBy('name')->get();

        $latestDevices = Device::with('category')
            ->latest('id')
            ->take(6)
            ->get();

        $availableCount = Device::where('status', Device::STATUS_AVAILABLE)->count();
        $totalCount = Device::count();

        return view('home.index', compact('categories', 'latestDevices', 'availableCount', 'totalCount'));
    }
}
