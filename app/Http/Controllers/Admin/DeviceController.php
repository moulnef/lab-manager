<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeviceRequest;
use App\Models\Category;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * CRUD Bảng 2 – Thiết bị: upload ảnh (Storage), xóa mềm (SoftDeletes),
 * tìm kiếm + phân trang, khôi phục và xem lịch sử mượn.
 */
class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['keyword', 'category_id', 'status']);

        $devices = Device::with('category')
            ->filter($filters)
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();
        $statusCounts = Device::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        $trashedCount = Device::onlyTrashed()->count();

        return view('admin.devices.index', compact('devices', 'categories', 'filters', 'statusCounts', 'trashedCount'));
    }

    public function create(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $device = new Device([
            'status' => Device::STATUS_AVAILABLE,
            'category_id' => $request->integer('category_id') ?: null,
        ]);

        return view('admin.devices.create', compact('device', 'categories'));
    }

    public function store(DeviceRequest $request)
    {
        $data = $request->safe()->except(['image', 'remove_image']);

        if ($data['status'] === Device::STATUS_BORROWED) {
            return back()->withInput()->withErrors([
                'status' => 'Trạng thái "Đang mượn" chỉ được đặt tự động khi duyệt phiếu mượn.',
            ]);
        }

        if ($request->hasFile('image')) {
            // Lưu vào storage/app/public/devices, truy cập qua /storage/devices/...
            $data['image'] = $request->file('image')->store('devices', 'public');
        }

        $device = Device::create($data);

        return redirect()->route('admin.devices.index')
            ->with('success', "Đã thêm thiết bị \"{$device->name}\" ({$device->code}).");
    }

    /** Xem chi tiết + lịch sử mượn của 1 thiết bị. */
    public function show(Device $device)
    {
        $device->load('category');

        $transactions = $device->transactions()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('admin.devices.show', compact('device', 'transactions'));
    }

    public function edit(Device $device)
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.devices.edit', compact('device', 'categories'));
    }

    public function update(DeviceRequest $request, Device $device)
    {
        $data = $request->safe()->except(['image', 'remove_image']);

        // Không cho đổi tay trạng thái của thiết bị đang có phiếu mượn hiệu lực
        $hasActiveLoan = $device->transactions()->where('status', 'approved')->exists();
        if ($hasActiveLoan && $data['status'] !== Device::STATUS_BORROWED) {
            return back()->withInput()->withErrors([
                'status' => 'Thiết bị đang được mượn. Hãy "Xác nhận trả" ở mục Phiếu mượn thay vì đổi trạng thái thủ công.',
            ]);
        }
        if (! $hasActiveLoan && $data['status'] === Device::STATUS_BORROWED) {
            return back()->withInput()->withErrors([
                'status' => 'Trạng thái "Đang mượn" chỉ được đặt tự động khi duyệt phiếu mượn.',
            ]);
        }

        if ($request->hasFile('image')) {
            $this->deleteImage($device);
            $data['image'] = $request->file('image')->store('devices', 'public');
        } elseif ($request->boolean('remove_image')) {
            $this->deleteImage($device);
            $data['image'] = null;
        }

        $device->update($data);

        return redirect()->route('admin.devices.index')
            ->with('success', "Đã cập nhật thiết bị \"{$device->name}\".");
    }

    /** Xóa mềm: chỉ đánh dấu deleted_at, có thể khôi phục. */
    public function destroy(Device $device)
    {
        if ($device->status === Device::STATUS_BORROWED) {
            return back()->with('error', "Không thể xóa \"{$device->name}\" vì thiết bị đang được mượn.");
        }

        $device->delete();

        return back()->with('success', "Đã chuyển \"{$device->name}\" vào thùng rác. Bạn có thể khôi phục lại bất cứ lúc nào.");
    }

    /** Danh sách thiết bị đã xóa mềm. */
    public function trash(Request $request)
    {
        $keyword = trim((string) $request->query('keyword'));

        $devices = Device::onlyTrashed()
            ->with('category')
            ->filter(['keyword' => $keyword])
            ->latest('deleted_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.devices.trash', compact('devices', 'keyword'));
    }

    /** Khôi phục thiết bị đã xóa mềm. */
    public function restore(int $id)
    {
        $device = Device::onlyTrashed()->findOrFail($id);
        $device->restore();

        return redirect()->route('admin.devices.trash')
            ->with('success', "Đã khôi phục thiết bị \"{$device->name}\".");
    }

    private function deleteImage(Device $device): void
    {
        if ($device->image && Storage::disk('public')->exists($device->image)) {
            Storage::disk('public')->delete($device->image);
        }
    }
}
