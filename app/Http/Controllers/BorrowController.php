<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowRequest;
use App\Models\Device;
use App\Models\Transaction;

class BorrowController extends Controller
{
    public function store(BorrowRequest $request)
    {
        $device = Device::findOrFail($request->device_id);

        if ($device->status !== 'available') {
            return back()->withInput()->withErrors(['device_id' => 'Thiết bị này hiện không sẵn sàng để mượn.']);
        }

        $t = new Transaction();
        $t->user_id = auth()->id() ?? 2; // tạm dùng 2 đến khi có đăng nhập (Huy)
        $t->device_id = $device->id;
        $t->borrow_date = $request->borrow_date;
        $t->return_date = $request->return_date;
        $t->status = 'pending'; // chờ Admin duyệt
        $t->save();

        return redirect()->route('user.devices.show', $device)
            ->with('success', 'Đã gửi yêu cầu mượn. Vui lòng chờ quản trị viên duyệt.');
    }
}
