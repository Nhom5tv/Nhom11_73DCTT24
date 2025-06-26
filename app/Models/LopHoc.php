<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LopHoc extends Model
{
     protected $table = 'lop'; // Tên bảng trong CSDL
    protected $primaryKey = 'ma_lop'; // Khóa chính
    public $incrementing = true; // Không phải auto-increment
    public $timestamps = false; // Bảng không có created_at, updated_at

    protected $fillable = [
        'ma_lop',
        'ma_mon',
        'hoc_ky',
        'ma_giang_vien',
        'lich_hoc',
        'trang_thai',
    ];
    public function dangKyMonHocs()
    {
        return $this->hasMany(DangKyMonHoc::class, 'ma_lop', 'ma_lop');
    }
}
?>
