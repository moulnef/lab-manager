<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowRequest;
use App\Models\Device;
use App\Models\Transaction;

class BorrowController extends Controller
{
    // Tạm dùng user 2 đến khi có đăng nhập (Huy); có đăng nhập thì tự lấy user thật
    private function currentUserId(): int
    {
        return (int) (auth()->id() ?? 2);
    }

    // Lịch sử mượn của tôi
    public function index()
    {
        $transactions = Transaction::with('device.category')
            ->where('user_id', $this->currentUserId())
            ->latest('id')
            ->paginate(10);

        return view('borrow.history', compact('transactions'));
    }

    // Gửi yêu cầu mượn
    public function store(BorrowRequest $request)
    {
        $device = Device::findOrFail($request->device_id);

        if ($device->status !== 'available') {
            return back()->withInput()->withErrors(['device_id' => 'Thiết bị này hiện không sẵn sàng để mượn.']);
        }

        $daGui = Transaction::where('user_id', $this->currentUserId())
            ->where('device_id', $device->id)
            ->where('status', 'pending')
            ->exists();

        if ($daGui) {
            return back()->withInput()->withErrors(['device_id' => 'Bạn đã gửi yêu cầu mượn thiết bị này, đang chờ duyệt.']);
        }

        $t = new Transaction();
        $t->user_id = $this->currentUserId();
        $t->device_id = $device->id;
        $t->borrow_date = $request->borrow_date;
        $t->return_date = $request->return_date;
        $t->status = 'pending'; // chờ Admin duyệt
        $t->save();

        return redirect()->route('user.borrows')
            ->with('success', 'Đã gửi yêu cầu mượn. Vui lòng chờ quản trị viên duyệt.');
    }

    // Hủy yêu cầu (chỉ khi đang chờ duyệt)
    public function cancel(Transaction $transaction)
    {
        abort_unless((int) $transaction->user_id === $this->currentUserId(), 403);

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Chỉ có thể hủy yêu cầu đang chờ duyệt.');
        }

        $transaction->status = 'cancelled';
        $transaction->save();

        return redirect()->route('user.borrows')->with('success', 'Đã hủy yêu cầu mượn.');
    }
}
