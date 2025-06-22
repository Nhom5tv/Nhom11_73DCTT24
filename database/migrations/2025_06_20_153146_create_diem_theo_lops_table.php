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
        Schema::create('diem_theo_lops', function (Blueprint $table) {
            $table->id('ma_dct');  // Cột primary key, auto-increment
            $table->integer('ma_lop');
            $table->string('ma_sinh_vien', 10);
            $table->integer('lan_hoc');
            $table->decimal('diem_chuyen_can', 5, 2)->default(0.00);
            $table->decimal('diem_giua_ky', 5, 2)->default(0.00);
            $table->decimal('diem_cuoi_ky', 5, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diem_theo_lops');
    }
};
