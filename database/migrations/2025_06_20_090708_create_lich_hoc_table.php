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
        Schema::create('lich_hoc', function (Blueprint $table) {
        $table->increments('id_lich_hoc'); // tự tăng, khóa chính
        $table->string('ma_mon_hoc', 10);
        $table->integer('so_luong');
        $table->integer('so_luong_toi_da')->nullable();
        $table->string('lich_hoc')->nullable();
        $table->string('trang_thai')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_hoc');
    }
};
