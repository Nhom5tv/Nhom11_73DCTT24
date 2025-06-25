<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // ✅ Đúng


class Nganh extends Model
{
    use HasFactory;

    protected $table = 'nganh';
    protected $primaryKey = 'ma_nganh';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'ten_nganh',
        'ma_khoa',
        'thoi_gian_dao_tao',
        'bac_dao_tao',
    ];
}
