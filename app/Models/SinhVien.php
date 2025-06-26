<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // ✅ Đúng


class SinhVien extends Model
{
    use HasFactory;

    protected $table = 'sinh_vien';
    protected $primaryKey = 'ma_sinh_vien';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ma_sinh_vien',
        'user_id',
        'ma_khoa',
        'ma_nganh',
        'ho_ten',
        'ngay_sinh',
        'gioi_tinh',
        'que_quan',
        'email',
        'so_dien_thoai',
        'khoa_hoc',
    ];
    public function khoa()
{
    return $this->belongsTo(Khoa::class, 'ma_khoa');
}
    public function dangKyMonHocs()
{
    return $this->hasMany(DangKyMonHoc::class, 'ma_sinh_vien', 'ma_sinh_vien');
}
}
