<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dang_ky_mon_hoc', function (Blueprint $table) {
            $table->increments('ma_dang_ky');
            $table->string('ma_mon', 10);
            $table->string('ma_sinh_vien', 10);
            $table->unsignedInteger('ma_lop')->nullable();
            $table->string('lich_hoc_du_kien', 255)->nullable();
            $table->string('trang_thai', 20)->nullable();
        });
    }
    public function down()
    {
        Schema::dropIfExists('dang_ky_mon_hoc');
    }
};
