<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    protected $table = 'hoa_don';
    protected $primaryKey = 'ma_hoa_don';
    public $timestamps = false;

    protected $fillable = [
        'ma_sinh_vien',
        'ma_khoan_thu',
        'ngay_thanh_toan',
        'so_tien_da_nop',
        'hinh_thuc_thanh_toan',
        'noi_dung',
        'da_huy',
    ];
    protected $casts = [
        'da_huy' => 'boolean',
    ];

    // Liên kết với sinh viên
    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'ma_sinh_vien', 'ma_sinh_vien');
    }

    // Liên kết với khoản thu
    public function khoanThu()
    {
        return $this->belongsTo(KhoanThu::class, 'ma_khoan_thu', 'ma_khoan_thu');
    }
}

