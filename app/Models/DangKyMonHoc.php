<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DangKyMonHoc extends Model
{
    protected $table = 'dang_ky_mon_hoc'; // Tên bảng
    protected $primaryKey = 'ma_dang_ky'; // Khóa chính
    public $incrementing = true; // Tự tăng
    public $timestamps = false; // Không có created_at, updated_at

    protected $fillable = [
        'ma_mon',
        'ma_sinh_vien',
        'ma_lop',
        'lich_hoc_du_kien',
        'trang_thai',
    ];

    // Quan hệ với môn học (nếu có model Mon)
    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'ma_mon', 'ma_mon');
    }

    // Quan hệ với sinh viên (nếu có model SinhVien)
    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'ma_sinh_vien', 'ma_sinh_vien');
    }

    // Quan hệ với lớp học (nếu có model LopHoc)
    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'ma_lop', 'ma_lop');
    }
    public function lichHoc()
{
    return $this->belongsTo(LichHoc::class, 'ma_mon', 'ma_mon_hoc');
}
    // App\Models\DangKyMonHoc.php
    // App\Models\DangKyMonHoc.php

public function lop()
{
    return $this->belongsTo(\App\Models\LopHoc::class, 'ma_lop', 'ma_lop');
}

}
