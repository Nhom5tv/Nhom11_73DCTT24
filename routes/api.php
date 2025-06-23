<?php

use App\Http\Controllers\Api\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KhoanThuController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Api\MonHocController;
use App\Http\Controllers\Api\TaiKhoanController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->get('/me', [AuthController::class, 'me']);
Route::middleware(['auth:api', RoleMiddleware::class . ':admin'])->get('/admin', function () {
    return response()->json(['msg' => 'Chào Admin']);
});

Route::middleware(['auth:api', RoleMiddleware::class . ':giaovien'])->get('/giaovien', function () {
    return response()->json(['msg' => 'Chào Giáo viên']);
});

Route::middleware(['auth:api', RoleMiddleware::class . ':sinhvien'])->get('/sinhvien', function () {
    return response()->json(['msg' => 'Chào Sinh viên']);
});


Route::apiResource('/admin/monhoc', MonHocController::class);

// Api Quỳnh
//Quên Mật khẩu
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink']);
Route::post('/reset-password', [ForgotPasswordController::class, 'reset']);
// Chức năng quản lý tài khoản

Route::prefix('admin')->middleware('auth:api')->group(function () {
    Route::get('/taikhoan', [TaiKhoanController::class, 'index']);           // Lấy danh sách tài khoản
    Route::get('/taikhoan/{id}', [TaiKhoanController::class, 'show']);        // Chi tiết 1 tài khoản
    Route::post('/taikhoan', [TaiKhoanController::class, 'storeAdmin']); // Thêm mới tài khoản admin
    Route::put('/taikhoan/{id}', [TaiKhoanController::class, 'updateInfo']); // Cập nhật name, email
    Route::delete('/taikhoan/{id}', [TaiKhoanController::class, 'destroy']); // Xóa tài khoản
});
//Quản lý khoản thu
Route::prefix('admin')->middleware('auth:api')->group(function () {
    Route::get('/khoanthu', [KhoanThuController::class, 'index']);         // Lấy danh sách khoản thu
    Route::get('/khoanthu/{id}', [KhoanThuController::class, 'show']);    // Chi tiết khoản thu
    Route::post('/khoanthu', [KhoanThuController::class, 'store']);       // Thêm mới khoản thu
    Route::put('/khoanthu/{id}', [KhoanThuController::class, 'update']);  // Cập nhật khoản thu
    Route::delete('/khoanthu/{id}', [KhoanThuController::class, 'destroy']); // Xóa khoản thu
});


// Hết phần của Quỳnh
?>