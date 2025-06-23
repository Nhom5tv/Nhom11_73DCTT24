<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MienGiamSinhVienController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Api\DiemTheoLopController;
use App\Http\Controllers\Api\LichHocController;
use App\Http\Controllers\Api\LopHocController;
use App\Http\Controllers\Api\MonHocController;
use App\Http\Controllers\Api\SinhVienController;
use App\Http\Controllers\Api\GiangVienController;
use App\Http\Controllers\Api\NganhController;




Route::post('/login', [AuthController::class, 'login'])->name('login');

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
//phan cua Vu
//API routes for MonHoc
Route::prefix('admin')->middleware('auth:api')->group(function () {
    Route::get('/monhoc', [MonHocController::class, 'index']);
    Route::post('/monhoc', [MonHocController::class, 'store']);
    Route::get('/monhoc/{ma_mon}', [MonHocController::class, 'show']);
    Route::put('/monhoc/{ma_mon}', [MonHocController::class, 'update']);
    Route::delete('/monhoc/{ma_mon}', [MonHocController::class, 'destroy']);
});
//API routes for MienGiamSinhVien
Route::prefix('admin')->middleware('auth:api')->group(function(){
    Route::get('/miengiam',[MienGiamSinhVienController::class, 'index']);
    Route::post('/miengiam',[MienGiamSinhVienController::class, 'store']);
    Route::get('/miengiam/{ma_mien_giam}',[MienGiamSinhVienController::class, 'show']);
    Route::put('/miengiam/{ma_mien_giam}',[MienGiamSinhVienController::class, 'update']);
    Route::delete('/miengiam/{ma_mien_giam}',[MienGiamSinhVienController::class, 'destroy']);
});
//phan cua Vu

// Phần của giáo viên
Route::prefix('giaovien')->middleware(['auth:api', RoleMiddleware::class . ':giaovien'])->group(function () {
    // Lấy điểm theo lớp
    Route::get('/diem-theo-lop/{ma_lop}', [DiemTheoLopController::class, 'getData']);
    Route::put('/diem-theo-lop/{ma_sinh_vien}', [DiemTheoLopController::class, 'updateData']);
    // Lấy danh sách lớp học theo mã giảng viên
    Route::get('/dslophoc/{ma_giang_vien}', [LopHocController::class, 'getByMaGiangVien']);
});
//Phần của Dũng
Route::prefix('admin')->middleware(['auth:api', RoleMiddleware::class . ':admin'])->group(function () {
//Chức năng quản lý lịch học
    // 1. Lấy tất cả lịch học
    Route::get('/dslichhoc', [LichHocController::class, 'index']);
    // Tìm Kiếm
    Route::get('/dslichhoc', [LichHocController::class, 'timkiem']);
    // 2. Lấy chi tiết 1 lịch học theo ID
    Route::get('/dslichhoc/{id}', [LichHocController::class, 'show']);

    // 3. Tạo mới lịch học
    Route::post('/dslichhoc', [LichHocController::class, 'store']);

    // 4. Cập nhật lịch học theo ID
    Route::put('/dslichhoc/{id}', [LichHocController::class, 'update']);

    // 5. Xoá lịch học theo ID
    Route::delete('/dslichhoc/{id}', [LichHocController::class, 'destroy']);
    // 6.Đóng tất cả lớp học đang mở
    Route::put('/dslichhoc/dongtatca', [LichHocController::class, 'dongTatCa']);
    //Chức năng quản lý lớp học
    Route::get('/dslophoc', [LopHocController::class, 'index']);
    Route::get('/dslophoc/{id}', [LopHocController::class, 'show']);
    Route::post('/dslophoc', [LopHocController::class, 'store']);
    Route::put('/dslophoc/{id}', [LopHocController::class, 'update']);
    Route::delete('/dslophoc/{id}', [LopHocController::class, 'destroy']);
   
});
//hết phần của Dũng

Route::apiResource('/admin/monhoc', MonHocController::class);
Route::prefix('admin')->middleware(['auth:api', RoleMiddleware::class . ':admin'])->group(function () {
   
});
//phần của phanh
Route::prefix('admin')->middleware(['auth:api', RoleMiddleware::class . ':admin'])->group(function () {
    //sinh viên
    Route::get('/sinhvien', [SinhVienController::class, 'index']);
    Route::post('/sinhvien', [SinhVienController::class, 'store']);
    Route::get('/sinhvien/{ma_sinh_vien}', [SinhVienController::class, 'show']);
    Route::put('/sinhvien/{ma_sinh_vien}', [SinhVienController::class, 'update']);
    Route::delete('/sinhvien/{ma_sinh_vien}', [SinhVienController::class, 'destroy']);
    //giảng viên
    Route::get('/giangvien', [GiangVienController::class, 'index']);
    Route::post('/giangvien', [GiangVienController::class, 'store']);
    Route::get('/giangvien/{ma_giang_vien}', [GiangVienController::class, 'show']);
    Route::put('/giangvien/{ma_giang_vien}', [GiangVienController::class, 'update']);
    Route::delete('/giangvien/{ma_giang_vien}', [GiangVienController::class, 'destroy']);
    Route::get('/nganh', [NganhController::class, 'index']);
    //ngành
    Route::post('/nganh', [NganhController::class, 'store']);
    Route::get('/nganh/{id}', [NganhController::class, 'show']);
    Route::put('/nganh/{id}', [NganhController::class, 'update']);
    Route::delete('/nganh/{id}', [NganhController::class, 'destroy']);

});
