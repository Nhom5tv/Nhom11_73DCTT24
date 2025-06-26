<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class GiangVien extends Model
{
 use HasFactory;

    protected $table = 'giang_vien';
    protected $primaryKey = 'ma_giang_vien';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ma_giang_vien',
        'user_id',
        'ma_khoa',
        'ho_ten',
        'email',
        'so_dien_thoai',
        'chuyen_nganh',
    ];
     public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}


