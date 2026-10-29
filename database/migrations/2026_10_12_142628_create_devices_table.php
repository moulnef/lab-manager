<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng 2 – Thiết bị (devices).
     * Quan hệ: thuộc về 1 danh mục (belongsTo), có nhiều phiếu mượn (hasMany).
     * Có cột deleted_at để xóa mềm (SoftDeletes).
     */
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();                    // Không cho xóa danh mục còn thiết bị
            $table->string('code', 30)->unique();        // Mã thiết bị, vd: LAB-LT-001
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();         // Đường dẫn ảnh trong storage/app/public
            $table->enum('status', ['available', 'borrowed', 'maintenance'])->default('available');
            $table->timestamps();
            $table->softDeletes();                       // deleted_at

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
