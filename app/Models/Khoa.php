<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Khoa extends Model
{
    use HasFactory;

    protected $table = 'khoa';
    protected $primaryKey = 'ma_khoa';
    public $timestamps = false;

    protected $fillable = [
        'ten_khoa',
        'lien_he',
        'ngay_thanh_lap',
        'tien_moi_tin_chi',
    ];
}
