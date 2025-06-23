<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhoanThu extends Model
{
    use HasFactory;

    protected $table = 'khoan_thu';
    protected $primaryKey = 'ma_khoan_thu';
    public $timestamps = true;

    protected $fillable = [
        'ten_khoan_thu',
        'loai_khoan_thu',
        'so_tien',
        'ngay_tao',
        'han_nop'
    ];
}
