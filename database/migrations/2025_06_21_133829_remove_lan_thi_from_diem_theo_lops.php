<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveLanThiFromDiemTheoLops extends Migration
{
    public function up()
    {
        // Xóa cột lan_thi
        Schema::table('diem_theo_lops', function (Blueprint $table) {
            $table->dropColumn('lan_thi');
        });
    }

    public function down()
    {
        // Nếu cần roll back migration, có thể thêm lại cột lan_thi
        Schema::table('diem_theo_lops', function (Blueprint $table) {
            $table->integer('lan_thi'); // Giả sử lan_thi là kiểu integer
        });
    }
}
