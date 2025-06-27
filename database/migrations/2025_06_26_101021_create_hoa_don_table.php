<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hoa_don', function (Blueprint $table) {
            $table->id('ma_hoa_don');
            $table->string('ma_sinh_vien', 10);
            $table->unsignedBigInteger('ma_khoan_thu');
            $table->date('ngay_thanh_toan');
            $table->decimal('so_tien_da_nop', 12, 2);
            $table->string('hinh_thuc_thanh_toan', 50)->nullable();
            $table->string('noi_dung', 255)->nullable();

            $table->boolean('da_huy')->default(false); // thêm trường trạng thái hủy

            // Khóa ngoại
            $table->foreign('ma_khoan_thu')->references('ma_khoan_thu')->on('khoan_thu')->onDelete('cascade');
            $table->foreign('ma_sinh_vien')->references('ma_sinh_vien')->on('sinh_vien')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hoa_don');
    }
};
