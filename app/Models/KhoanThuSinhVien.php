<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhoanThuSinhVien extends Model
{
    protected $table = 'khoan_thu_sinh_vien';
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'ma_khoan_thu', 'ma_sinh_vien',
        'so_tien_ban_dau', 'so_tien_mien_giam',
        'so_tien_phai_nop', 'trang_thai_thanh_toan'
    ];
}
