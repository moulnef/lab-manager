<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Device;
use Illuminate\Http\Request;

/**
 * Danh mục thiết bị cho người dùng (khu vực công khai).
 */
class DeviceCatalogController extends Controller
{
    /**
     * GET /devices?keyword=...&category_id=...&status=...&page=...
     * - Tìm kiếm + phân trang, giữ nguyên từ khóa khi chuyển trang (withQueryString).
     * - Nếu là request AJAX: chỉ trả về phần HTML danh sách để cập nhật tức thì.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['keyword', 'category_id', 'status']);

        $devices = Device::with('category')
            ->filter($filters)
            ->orderByRaw("CASE WHEN status = 'available' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        // Cùng 1 URL nhưng trả về 2 dạng (trang đầy đủ / phần danh sách)
        // → header Vary để trình duyệt không lấy nhầm bản cache khi bấm Back.
        if ($request->ajax()) {
            return response()->view('devices._list', compact('devices'))
                ->header('Vary', 'X-Requested-With');
        }

        $categories = Category::orderBy('name')->get();

        return response()->view('devices.index', compact('devices', 'categories', 'filters'))
            ->header('Vary', 'X-Requested-With');
    }

    /** GET /devices/{device} – Trang chi tiết thiết bị. */
    public function show(Device $device)
    {
        $device->load('category');

        $related = Device::with('category')
            ->where('category_id', $device->category_id)
            ->whereKeyNot($device->id)
            ->orderByRaw("CASE WHEN status = 'available' THEN 0 ELSE 1 END")
            ->take(3)
            ->get();

        // Số lượt đã mượn thành công (để hiển thị độ "phổ biến")
        $borrowCount = $device->transactions()
            ->whereIn('status', ['approved', 'returned'])
            ->count();

        return view('devices.show', compact('device', 'related', 'borrowCount'));
    }
}
