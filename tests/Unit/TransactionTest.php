<?php

namespace Tests\Unit;

use App\Models\Device;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    public function test_phieu_dang_muon_qua_han(): void
    {
        $tx = new Transaction(['status' => Transaction::STATUS_APPROVED]);
        $tx->return_date = Carbon::yesterday();
        $this->assertTrue($tx->isOverdue());

        $tx->status = Transaction::STATUS_RETURNED;
        $this->assertFalse($tx->isOverdue());
    }

    public function test_nhan_trang_thai_tieng_viet(): void
    {
        $this->assertSame('Sẵn sàng', (new Device(['status' => 'available']))->status_label);
        $this->assertSame('Chờ duyệt', (new Transaction(['status' => 'pending']))->status_label);
    }
}
