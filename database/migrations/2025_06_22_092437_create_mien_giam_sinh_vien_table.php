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
        Schema::create('mien_giam_sinh_vien', function (Blueprint $table) {
            $table->id('ma_mien_giam');
            $table->string('ma_sinh_vien', 10);
            $table->decimal('muc_tien', 12, 2);
            $table->string('loai_mien_giam', 50)->nullable();
            $table->string('ghi_chu', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mien_giam_sinh_vien');
    }
};
