<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng 1 – Danh mục thiết bị (categories).
     * Quan hệ: 1 danh mục có nhiều thiết bị (hasMany).
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();      // Tên danh mục
            $table->string('icon', 50)->nullable();     // Tên icon Bootstrap Icons (vd: laptop)
            $table->text('description')->nullable();    // Mô tả ngắn
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
