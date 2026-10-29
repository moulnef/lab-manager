<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Bảng điều khiển quản trị – thống kê bằng hàm tổng hợp COUNT + GROUP BY.
 */
class DashboardController extends Controller
{
    public function index()
    {
        // 1. Số thiết bị theo trạng thái
        //    SELECT status, COUNT(*) AS total FROM devices WHERE deleted_at IS NULL GROUP BY status
        $deviceByStatus = Device::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // 2. Số thiết bị theo danh mục (JOIN + GROUP BY)
        $deviceByCategory = DB::table('categories')
            ->leftJoin('devices', function ($join) {
                $join->on('devices.category_id', '=', 'categories.id')
                    ->whereNull('devices.deleted_at');
            })
            ->select('categories.name', DB::raw('COUNT(devices.id) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->get();

        // 3. Số phiếu mượn theo trạng thái
        $transactionByStatus = Transaction::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // 4. Top 5 thiết bị được mượn nhiều nhất
        $topDevices = Device::withTrashed()
            ->withCount(['transactions as borrow_count' => fn ($q) => $q->whereIn('status', ['approved', 'returned'])])
            ->orderByDesc('borrow_count')
            ->take(5)
            ->get()
            ->filter(fn ($device) => $device->borrow_count > 0);

        $stats = [
            'devices' => Device::count(),
            'available' => $deviceByStatus[Device::STATUS_AVAILABLE] ?? 0,
            'borrowed' => $deviceByStatus[Device::STATUS_BORROWED] ?? 0,
            'maintenance' => $deviceByStatus[Device::STATUS_MAINTENANCE] ?? 0,
            'users' => User::where('role', User::ROLE_USER)->count(),
            'pending' => $transactionByStatus[Transaction::STATUS_PENDING] ?? 0,
            'overdue' => Transaction::where('status', Transaction::STATUS_APPROVED)
                ->whereDate('return_date', '<', today())->count(),
        ];

        // 5. Phiếu mượn mới nhất (eager loading chống N+1)
        $recentTransactions = Transaction::with(['user', 'device'])
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'deviceByCategory', 'transactionByStatus', 'topDevices', 'recentTransactions'
        ));
    }
}
