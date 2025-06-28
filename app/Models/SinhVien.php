<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // ✅ Đúng
use Illuminate\Support\Facades\DB;
use App\Models\Khoa;
use Illuminate\Support\Facades\Log;

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
    return $this->belongsTo(Khoa::class, 'ma_khoa','ma_khoa');
}
public function dangKyMonHocs()
{
    return $this->hasMany(DangKyMonHoc::class, 'ma_sinh_vien', 'ma_sinh_vien');
}
public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
// public function tongTinChiDangKy(): int
// {
//     return DB::table('dang_ky_mon_hoc as dkmh')
//         ->join('lop as lh', 'dkmh.ma_lop', '=', 'lh.ma_lop')
//         ->join('mon_hoc as mh', 'dkmh.ma_mon', '=', 'mh.ma_mon')
//         ->where('dkmh.ma_sinh_vien', $this->ma_sinh_vien)
//         ->where('dkmh.trang_thai', 'Đã duyệt')
//         ->where('lh.trang_thai', 'Đang mở')
//         ->sum('mh.so_tin_chi');
//     // Ghi log SQL và binding
//     Log::info('SQL:', ['query' => $query->toSql(), 'bindings' => $query->getBindings()]);

//     return $query->sum('mh.so_tin_chi');
// }

public function tongTinChiDangKy(): int
{
    Log::info('GỌI HÀM TONGTINCHIDANGKY');

    $query = DB::table('dang_ky_mon_hoc as dkmh')
        ->join('lop as lh', 'dkmh.ma_lop', '=', 'lh.ma_lop')
        ->join('mon_hoc as mh', 'dkmh.ma_mon', '=', 'mh.ma_mon')
        ->where('dkmh.ma_sinh_vien', $this->ma_sinh_vien)
        ->where('dkmh.trang_thai', 'Đã duyệt')
        ->where('lh.trang_thai', 'Đang mở');

    // ✅ Ghi ra SQL query và bindings
    Log::info('SQL Tổng TC:', [
        'query' => $query->toSql(),
        'bindings' => $query->getBindings()
    ]);

    // ✅ Sau đó thực thi
    return $query->sum('mh.so_tin_chi');
}


public function nganh()
{
    return $this->belongsTo(Nganh::class, 'ma_nganh', 'ma_nganh');
}


}
