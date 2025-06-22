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
        Schema::create('lop', function (Blueprint $table) {
            $table->increments('ma_lop')->primary();
            $table->string('ma_mon', 10);
            $table->string('hoc_ky', 9);
            $table->string('ma_giang_vien', 10);
            $table->string('lich_hoc', 255)->nullable();
            $table->string('trang_thai', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lop_hoc');
    }
};
