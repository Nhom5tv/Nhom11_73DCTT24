<?php

use Illuminate\Support\Facades\Mail;
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

//Phần của Quỳnh
//Quản lý tài khoản
Route::get('/admin/taikhoan', function () {
    return view('pages.admin.tai_khoan.index');
});
Route::get('/admin/taikhoan/create', function () {
    return view('pages.admin.tai_khoan.create');
});
Route::get('/admin/taikhoan/{id}/edit', function ($id) {
    return view('pages.admin.tai_khoan.edit', ['id' => $id]);
});
//Quên mật khẩu
Route::get('/forgot-password', function () {
    return view('forgot_password');
});
Route::get('/reset-password/{token}', function ($token) {
    $email = request()->query('email'); // lấy email từ query string
    return view('reset_password', compact('token', 'email'));
})->name('password.reset');

// Quản lý khoản thu
Route::get('/admin/khoanthu', function () {
    return view('pages.admin.khoan_thu.index');
});
Route::get('/admin/taikhoan/create', function () {
    return view('pages.admin.khoan_thu.create');
});
Route::get('/admin/taikhoan/{id}/edit', function ($id) {
    return view('pages.admin.khoan_thu.edit', ['id' => $id]);
});
//Hết phần của Quỳnh
