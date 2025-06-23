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
        Schema::create('khoan_thu', function (Blueprint $table) {
            $table->id('ma_khoan_thu');
            $table->string('ten_khoan_thu');
            $table->enum('loai_khoan_thu', ['Học phí', 'BHYT', 'Khác'])->nullable();
            $table->decimal('so_tien', 12, 2);
            $table->date('ngay_tao')->nullable();
            $table->date('han_nop')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khoan_thu');
    }
};
