<?php
namespace App\Http\Controllers;
use App\Models\Device;
use Illuminate\Http\Request;
use App\Services\DeviceService;
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
        ]);

        try {
            // Giả sử user ID = 2 đang đăng nhập mượn máy (Sau này sẽ thay bằng auth()->id())
            $userId = 2; 
            $deviceService->borrowDevice($request->device_id, $userId);

            return response()->json(['message' => 'Mượn thiết bị thành công!']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // Bổ sung vào bên dưới các hàm api cũ trong DeviceController.php

    // 1. Hiển thị danh sách thiết bị cho Admin
    public function index()
    {
        $devices = Device::orderBy('created_at', 'desc')->get();
        return view('admin.devices.index', compact('devices'));
    }

    // 2. Trả về form thêm mới thiết bị
    public function create()
    {
        return view('admin.devices.create');
    }

    // 3. Xử lý lưu thiết bị mới vào CSDL
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'status' => 'required|in:available,borrowed,maintenance'
        ]);

        Device::create($request->all());

        return redirect()->route('admin.devices.index')->with('success', 'Thêm thiết bị thành công!');
    }
}