<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\BorrowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Quản lý phiếu mượn: lọc, duyệt, từ chối, xác nhận trả.
 */
class TransactionController extends Controller
{
    public function __construct(private BorrowService $borrowService) {}

    public function index(Request $request)
    {
        $status = $request->query('status');
        $keyword = trim((string) $request->query('keyword'));
        $overdue = $request->boolean('overdue');

        $transactions = Transaction::with(['user', 'device.category'])
            ->when($status && array_key_exists($status, Transaction::STATUSES), fn ($q) => $q->where('status', $status))
            ->when($overdue, fn ($q) => $q->where('status', Transaction::STATUS_APPROVED)->whereDate('return_date', '<', today()))
            ->when($keyword !== '', function ($q) use ($keyword) {
                $q->where(function ($sub) use ($keyword) {
                    $sub->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$keyword}%")->orWhere('email', 'like', "%{$keyword}%"))
                        ->orWhereHas('device', fn ($d) => $d->withTrashed()->where('name', 'like', "%{$keyword}%")->orWhere('code', 'like', "%{$keyword}%"));
                });
            })
            // Ưu tiên phiếu chờ duyệt lên đầu
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 WHEN status = 'approved' THEN 1 ELSE 2 END")
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $counts = Transaction::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.transactions.index', compact('transactions', 'counts', 'status', 'keyword', 'overdue'));
    }

    public function approve(Transaction $transaction)
    {
        return $this->run(fn () => $this->borrowService->approve($transaction),
            'Đã duyệt phiếu #'.$transaction->id.'. Thiết bị chuyển sang trạng thái "Đang mượn".');
    }

    public function reject(Request $request, Transaction $transaction)
    {
        $note = $request->validate(['admin_note' => ['nullable', 'string', 'max:255']],
            ['admin_note.max' => 'Lý do từ chối không được vượt quá :max ký tự.'])['admin_note'] ?? null;

        return $this->run(fn () => $this->borrowService->reject($transaction, $note),
            'Đã từ chối phiếu #'.$transaction->id.'.');
    }

    public function markReturned(Transaction $transaction)
    {
        return $this->run(fn () => $this->borrowService->markReturned($transaction),
            'Đã xác nhận trả phiếu #'.$transaction->id.'. Thiết bị trở về trạng thái "Sẵn sàng".');
    }

    private function run(callable $action, string $successMessage)
    {
        try {
            $action();
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', $successMessage);
    }
}
