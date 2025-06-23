<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
    {
        Schema::create('giang_vien', function (Blueprint $table) {
            $table->string('ma_giang_vien', 10)->primary();
            $table->unsignedBigInteger('user_id'); // thêm user_id ngay sau ma_giang_vien
            $table->unsignedInteger('ma_khoa');
            $table->string('ho_ten', 50);
            $table->string('email', 50);
            $table->string('so_dien_thoai', 15)->nullable();
            $table->string('chuyen_nganh', 50)->nullable();
            $table->timestamps();

            // Khóa ngoại đến bảng users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('giang_vien');
    }
};
