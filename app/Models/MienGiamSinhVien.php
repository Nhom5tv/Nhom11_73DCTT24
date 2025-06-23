<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MienGiamSinhVien extends Model
{
    protected $table = 'mien_giam_sinh_vien';

    protected $primaryKey = 'ma_mien_giam';

    public $timestamps = false;

    protected $fillable = [
        'ma_mien_giam',
        'ma_sinh_vien',
        'muc_tien',
        'loai_mien_giam',
        'ghi_chu',
    ];

}
