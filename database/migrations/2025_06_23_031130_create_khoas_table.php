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
        Schema::create('khoa', function (Blueprint $table) {
            $table->increments('ma_khoa');
            $table->string('ten_khoa', 100)->charset('utf8mb4');
            $table->string('lien_he', 50)->nullable()->charset('utf8mb4');
            $table->date('ngay_thanh_lap')->nullable();
            $table->decimal('tien_moi_tin_chi', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khoa');
    }
};
