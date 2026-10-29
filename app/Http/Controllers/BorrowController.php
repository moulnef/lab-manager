<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowRequest;
use App\Models\Device;
use App\Models\Transaction;
use App\Services\BorrowService;
use Illuminate\Http\Request;
use RuntimeException;

/**
 * Chức năng mượn thiết bị của người dùng đã đăng nhập.
 */
class BorrowController extends Controller
{
    public function __construct(private BorrowService $borrowService) {}

    /** GET /borrow/create?device=ID – Form gửi yêu cầu mượn. */
    public function create(Request $request)
    {
        $devices = Device::with('category')
            ->where('status', Device::STATUS_AVAILABLE)
            ->orderBy('name')
            ->get();

        $selectedId = (int) $request->query('device');

        return view('borrow.create', [
            'devices' => $devices,
            'selectedId' => $selectedId,
            'maxDays' => BorrowRequest::MAX_DAYS,
        ]);
    }

    /** POST /borrow – Lưu yêu cầu mượn (đã được BorrowRequest kiểm tra). */
    public function store(BorrowRequest $request)
    {
        $transaction = $this->borrowService->request($request->user(), $request->validated());
        $transaction->load('device');

        return redirect()->route('borrow.history')
            ->with('success', "Đã gửi yêu cầu mượn \"{$transaction->device->name}\". Vui lòng chờ quản trị viên duyệt.");
    }

    /** GET /my-borrows – Lịch sử mượn của tôi. */
    public function history(Request $request)
    {
        $status = $request->query('status');

        $transactions = $request->user()->transactions()
            ->with('device.category')
            ->when($status && array_key_exists($status, Transaction::STATUSES), fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $counts = $request->user()->transactions()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('borrow.history', compact('transactions', 'counts', 'status'));
    }

    /** PATCH /my-borrows/{transaction}/cancel – Hủy yêu cầu đang chờ. */
    public function cancel(Request $request, Transaction $transaction)
    {
        // Chỉ chủ phiếu mới được hủy
        abort_unless((int) $transaction->user_id === (int) $request->user()->id, 403, 'Bạn không thể hủy yêu cầu của người khác.');

        try {
            $this->borrowService->cancel($transaction);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Đã hủy yêu cầu mượn thiết bị.');
    }
}
