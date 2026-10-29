<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng nghiệp vụ – Phiếu mượn thiết bị (transactions).
     * Vòng đời: pending (chờ duyệt) → approved (đang mượn) → returned (đã trả)
     *           pending → rejected (bị từ chối) | cancelled (người dùng hủy)
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->date('borrow_date');                 // Ngày mượn dự kiến
            $table->date('return_date');                 // Ngày trả dự kiến
            $table->string('purpose', 500)->nullable();  // Mục đích sử dụng
            $table->enum('status', ['pending', 'approved', 'returned', 'rejected', 'cancelled'])
                ->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('returned_at')->nullable(); // Thời điểm trả thực tế
            $table->string('admin_note', 255)->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
