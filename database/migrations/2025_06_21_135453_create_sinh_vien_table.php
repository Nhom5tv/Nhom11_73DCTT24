<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
    {
        Schema::create('sinh_vien', function (Blueprint $table) {
            $table->string('ma_sinh_vien', 10)->primary();
            $table->unsignedBigInteger('user_id'); // Liên kết users
            $table->integer('ma_khoa');
            $table->integer('ma_nganh');
            $table->string('ho_ten', 50);
            $table->date('ngay_sinh');
            $table->string('gioi_tinh', 10)->nullable();
            $table->string('que_quan', 100)->nullable();
            $table->string('email', 50);
            $table->string('so_dien_thoai', 15)->nullable();
            $table->integer('khoa_hoc');

            // Khóa ngoại tới bảng users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sinh_vien');
    }
};
