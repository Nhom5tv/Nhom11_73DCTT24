<?php

use Illuminate\Support\Facades\Route;
use App\Models\LichHoc;
use App\Models\DiemTheoLop;

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
//đạt
Route::get('/giaovien/diem', function () {
    return view('pages.giaovien.diem');
});

Route::get('/giaovien/DSdiemgv', function () {
    return view('pages.giaovien.DSdiemgv');
});

Route::get('/giaovien/diem-theo-lop/{id}/edit', function ($id) {
    $diem = DiemTheoLop::findOrFail($id);
    return view('pages.giaovien.edit', compact('diem'));
});

Route::get('/giaovien/diem-theo-lop/{ma_lop}', function ($ma_lop) {
    return view('pages.giaovien.DSdiemgv', compact('ma_lop'));
});



Route::get('/admin/monhoc', function () {
    return view('pages.admin.monhoc');
});
