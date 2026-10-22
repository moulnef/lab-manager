<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceCatalogController extends Controller
{
    public function home()
    {
        return view('home', [
            'devices' => Device::with('category')->latest('id')->take(6)->get(),
            'total' => Device::count(),
            'available' => Device::where('status', 'available')->count(),
            'categories' => Category::count(),
        ]);
    }

    public function index(Request $request)
    {
        $devices = Device::with('category')
            ->when($request->filled('keyword'), fn ($q) => $q->where('name', 'like', '%' . $request->keyword . '%'))
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->category_id))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderBy('id')
            ->paginate(9)
            ->withQueryString();

        // Lọc bằng AJAX: chỉ trả về phần danh sách + phân trang
        if ($request->ajax()) {
            return view('devices._list', compact('devices'));
        }

        return view('devices.index', [
            'devices' => $devices,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function show(Device $device)
    {
        $device->load('category');

        return view('devices.show', compact('device'));
    }
}
