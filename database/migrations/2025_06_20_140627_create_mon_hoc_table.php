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
        Schema::create('mon_hoc', function (Blueprint $table) {
            $table->string('ma_mon', 10)->primary();
            $table->string('ten_mon', 100);
            $table->unsignedInteger('ma_nganh');
            $table->unsignedInteger('so_tin_chi')->nullable();
            $table->unsignedInteger('so_tiet')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mon_hoc');
    }
};
