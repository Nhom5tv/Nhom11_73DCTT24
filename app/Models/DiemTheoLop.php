<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiemTheoLop extends Model
{
    use HasFactory;

    protected $table = 'diem_theo_lops';
    protected $primaryKey = 'ma_dct'; // Chỉ định khóa chính là ma_dct
    public $incrementing = true; // Xác nhận auto-increment
    protected $keyType = 'int'; // Loại khóa là bigint

    // Tên bảng trong cơ sở dữ liệu
    public $timestamps = false;


    // Các trường có thể được gán giá trị (mass assignment)
    protected $fillable = [
        'ma_lop', 
        'ma_sinh_vien', 
        'lan_hoc', 
        'diem_chuyen_can', 
        'diem_giua_ky', 
        'diem_cuoi_ky'
    ];

//     public function dangKyMonHoc()
// {
//     return $this->belongsTo(DangKyMonHoc::class, ['ma_sinh_vien', 'ma_lop'], ['ma_sinh_vien', 'ma_lop']);
// }
public function dangKyMonHoc()
{
    return $this->belongsTo(DangKyMonHoc::class, 'ma_lop', 'ma_lop')
               ->where('ma_sinh_vien', $this->ma_sinh_vien);
}
}
