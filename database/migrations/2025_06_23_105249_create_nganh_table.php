<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('nganh', function (Blueprint $table) {
        $table->id('ma_nganh'); // PRIMARY KEY
        $table->string('ten_nganh', 100);
$table->integer('ma_khoa'); // hoặc unsignedBigInteger nếu bạn muốn giữ kiểu
        $table->float('thoi_gian_dao_tao')->nullable();
        $table->string('bac_dao_tao', 50)->nullable();
        $table->timestamps();

        // Ràng buộc khóa ngoại
    });
}

};
