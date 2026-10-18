<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use App\Services\DeviceService;
use Illuminate\Support\Facades\DB;
use Exception;

class DeviceController extends Controller
{
    // Hàm này trả về JSON cho Việt làm giao diện User
    public function getDevices()
    {
        $devices = Device::all();
        return response()->json($devices);
    }

    public function borrow(Request $request, DeviceService $deviceService)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,id',
        ], [
            'device_id.required' => 'Vui lòng chọn thiết bị muốn mượn.',
            'device_id.exists' => 'Thiết bị không tồn tại trong hệ thống.',
        ]);

        try {
            // Sử dụng ID của user đã đăng nhập, nếu chưa đăng nhập dùng ID = 2 làm fallback
            $userId = auth()->id() ?? 2; 
            $deviceService->borrowDevice($request->device_id, $userId);

            return response()->json(['message' => 'Mượn thiết bị thành công!']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // ==========================================
    // CÁC HÀM QUẢN TRỊ DÀNH CHO ADMIN (HUY)
    // ==========================================

    /**
     * 1. Hiển thị danh sách thiết bị cho Admin
     * Áp dụng MySQL nâng cao GROUP BY để thống kê nhanh số lượng theo trạng thái
     */
    public function index(Request $request)
    {
        $statusFilter = $request->query('status');
        $search = $request->query('search');

        $query = Device::orderBy('created_at', 'desc');

        if (!empty($statusFilter)) {
            $query->where('status', $statusFilter);
        }

        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
        }

        $devices = $query->paginate(15)->withQueryString();

        // Thống kê bằng GROUP BY
        $statusCounts = Device::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $totalCount = Device::count();

        return view('admin.devices.index', compact('devices', 'statusCounts', 'totalCount', 'statusFilter', 'search'));
    }

    /**
     * 2. Trả về form thêm mới thiết bị
     */
    public function create()
    {
        return view('admin.devices.create');
    }

    /**
     * 3. Xử lý lưu thiết bị mới vào CSDL (Method POST)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'type' => 'required|string|max:100',
            'status' => 'required|in:available,borrowed,maintenance',
        ], [
            'name.required' => 'Tên thiết bị không được để trống.',
            'name.min' => 'Tên thiết bị phải có ít nhất 3 ký tự.',
            'type.required' => 'Loại thiết bị không được để trống.',
            'status.required' => 'Trạng thái thiết bị không được để trống.',
            'status.in' => 'Trạng thái thiết bị không hợp lệ.',
        ]);

        Device::create($validated);

        return redirect()->route('admin.devices.index')
            ->with('success', 'Thêm thiết bị mới "' . $validated['name'] . '" thành công!');
    }

    /**
     * 4. Trả về form chỉnh sửa thiết bị (Method GET)
     */
    public function edit(Device $device)
    {
        return view('admin.devices.edit', compact('device'));
    }

    /**
     * 5. Xử lý cập nhật thông tin thiết bị (Method PUT)
     */
    public function update(Request $request, Device $device)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'type' => 'required|string|max:100',
            'status' => 'required|in:available,borrowed,maintenance',
        ], [
            'name.required' => 'Tên thiết bị không được để trống.',
            'name.min' => 'Tên thiết bị phải có ít nhất 3 ký tự.',
            'type.required' => 'Loại thiết bị không được để trống.',
            'status.required' => 'Trạng thái thiết bị không được để trống.',
            'status.in' => 'Trạng thái thiết bị không hợp lệ.',
        ]);

        $device->update($validated);

        return redirect()->route('admin.devices.index')
            ->with('success', 'Cập nhật thiết bị "' . $device->name . '" thành công!');
    }

    /**
     * 6. Xóa thiết bị khỏi hệ thống (Method DELETE)
     */
    public function destroy(Device $device)
    {
        $deviceName = $device->name;
        $device->delete();

        return redirect()->route('admin.devices.index')
            ->with('success', 'Đã xóa thiết bị "' . $deviceName . '" khỏi hệ thống.');
    }
}