<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonHoc extends Model
{
    // Nếu không dùng id auto-increment
    public $incrementing = false;

    // Nếu khóa chính không phải là id
    protected $primaryKey = 'ma_mon';

    // Nếu khóa chính không phải kiểu int
    protected $keyType = 'string';

    protected $table = 'mon_hoc';

    // Cho phép fill các trường này
    protected $fillable = [
        'ma_mon',
        'ten_mon',
        'ma_nganh',
        'so_tin_chi',
        'so_tiet'
    ];

    // Tắt timestamps nếu bảng không có created_at / updated_at
    public $timestamps = false;
}
