<?php

use Illuminate\Support\Facades\Route;
use App\Models\LichHoc;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/login', 'login');
Route::get('/giaovien', function () {
    return 'Đây là trang dành cho giáo viên (tạm thời)';
});

//Đây là phần của Dũng
//Đăng ký tín chỉ
Route::get('/dktinchi', function () {
    return view('sinhvien.dktinchi');
});
//Lịch Học
Route::get('admin/dslichhoc', function () {
    return view('admin.dslichhoc');
});
Route::get('admin/lichhocthem', function () {
    return view('admin.lichhocthem');
});
Route::get('admin/dslichhoc/{id}/lichhocsua', function ($id) {
    $LichHoc = LichHoc::find($id);
    if (!$LichHoc) {
        abort(404, 'Không tìm thấy lịch học');
    }
    return view('admin.lichhocsua', compact('LichHoc'));
});

//Lớp Học
    Route::get('admin/dslophoc', function () {
        return view('admin.dslophoc');
    });

//Hết Phần của Dũng



// Tất cả các role đều dùng chung 1 layout
Route::view('/admin', 'layout');
Route::view('/giaovien', 'layout');
Route::view('/sinhvien', 'layout');
