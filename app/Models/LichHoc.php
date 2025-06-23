<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichHoc extends Model
{
    protected $table = 'lich_hoc';
    protected $primaryKey = 'id_lich_hoc';
    public $timestamps = false;

     public $incrementing = true; // Đây là khoá tự tăng
    protected $fillable = [
        'ma_mon_hoc',
        'so_luong',
        'so_luong_toi_da',
        'lich_hoc',
        'trang_thai'
    ];

}
