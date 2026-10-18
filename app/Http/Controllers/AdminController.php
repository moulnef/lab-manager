<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Trang tổng quan Quản trị (Admin Dashboard)
     * Áp dụng MySQL nâng cao: Sử dụng GROUP BY và hàm tổng hợp COUNT(*)
     */
    public function dashboard()
    {
        // 1. Thống kê số lượng thiết bị theo từng trạng thái (GROUP BY status)
        $statusStats = Device::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Chuẩn hóa số lượng từng trạng thái
        $availableCount = $statusStats['available'] ?? 0;
        $borrowedCount = $statusStats['borrowed'] ?? 0;
        $maintenanceCount = $statusStats['maintenance'] ?? 0;
        $totalDevices = Device::count();

        // 2. Thống kê thiết bị theo chủng loại (GROUP BY type)
        $typeStats = Device::select('type', DB::raw('COUNT(*) as total'))
            ->groupBy('type')
            ->orderByDesc('total')
            ->get();

        // 3. Thống kê giao dịch và người dùng
        $totalUsers = User::count();
        $totalTransactions = Transaction::count();
        $activeBorrowCount = Transaction::where('status', 'active')->count();

        // 4. Lấy danh sách thiết bị mới cập nhật gần đây
        $recentDevices = Device::latest()->take(5)->get();

        // 5. Lấy danh sách giao dịch mượn máy gần đây
        $recentTransactions = Transaction::with(['user', 'device'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalDevices',
            'availableCount',
            'borrowedCount',
            'maintenanceCount',
            'totalUsers',
            'totalTransactions',
            'activeBorrowCount',
            'statusStats',
            'typeStats',
            'recentDevices',
            'recentTransactions'
        ));
    }
}
