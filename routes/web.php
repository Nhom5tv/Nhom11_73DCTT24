<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/login', 'login');
Route::get('/giaovien', function () {
    return 'Đây là trang dành cho giáo viên (tạm thời)';
});

// Tất cả các role đều dùng chung 1 layout
Route::view('/admin', 'layout');
Route::view('/giaovien', 'layout');
Route::view('/sinhvien', 'layout');

Route::get('/giaovien/diem', function () {
    return view('pages.giaovien.diem');
});
//URL for MonHoc, phan cua Vu
Route::get('/admin/monhoc', function () {
    return view('pages.admin.mon_hoc.index');
});
Route::get('/admin/monhoc/{ma_mon}/edit', function ($ma_mon) {
    return view('pages.admin.mon_hoc.edit', ['ma_mon' => $ma_mon]);
});
Route::get('/admin/monhoc/create', function () {
    return view('pages.admin.mon_hoc.create');
});
//URL for MienGiam 
Route::get('/admin/miengiam', function () {
    return view('pages.admin.mien_giam_sinh_vien.index');
});
Route::get('/admin/miengiam/{ma_mien_giam}/edit', function ($ma_mien_giam) {
    return view('pages.admin.mien_giam_sinh_vien.edit', ['ma_mien_giam' => $ma_mien_giam]);
});
Route::get('/admin/miengiam/create', function () {
    return view('pages.admin.mien_giam_sinh_vien.create');
});
//phan cua Vu