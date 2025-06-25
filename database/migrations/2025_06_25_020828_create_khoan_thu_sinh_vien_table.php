<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('khoan_thu_sinh_vien', function (Blueprint $table) {
        $table->unsignedBigInteger('ma_khoan_thu');
        $table->string('ma_sinh_vien', 10);
        $table->decimal('so_tien_ban_dau', 12, 2);
        $table->decimal('so_tien_mien_giam', 12, 2)->nullable();
        $table->decimal('so_tien_phai_nop', 12, 2)->nullable();
        $table->string('trang_thai_thanh_toan', 50)->nullable();
        
        $table->primary(['ma_khoan_thu', 'ma_sinh_vien']);
        $table->foreign('ma_khoan_thu')->references('ma_khoan_thu')->on('khoan_thu')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khoan_thu_sinh_vien');
    }
};
