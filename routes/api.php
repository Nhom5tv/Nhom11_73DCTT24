<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Api\DiemTheoLopController;
use App\Http\Controllers\Api\LichHocController;
use App\Http\Controllers\Api\LopHocController;
use App\Http\Controllers\Api\MonHocController;
use App\Http\Controllers\Api\KhoaController;

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
// Phần của Đạt
// Phần của giáo viên
Route::prefix('giaovien')->middleware(['auth:api', RoleMiddleware::class . ':giaovien'])->group(function () {
    // Lấy điểm theo lớp
    Route::get('/diem-theo-lop/{ma_lop}', [DiemTheoLopController::class, 'getData']);
    Route::put('/diem-theo-lop/{ma_sinh_vien}', [DiemTheoLopController::class, 'updateData']);
    // Lấy danh sách lớp học theo mã giảng viên
    Route::get('/dslophoc/{ma_giang_vien}', [LopHocController::class, 'getByMaGiangVien']);
});
Route::prefix('admin')
    ->middleware(['auth:api', RoleMiddleware::class . ':admin'])
    ->group(function () {
        Route::get('/dskhoa', [KhoaController::class, 'index']);
        Route::get('/timkiem-khoa', [KhoaController::class, 'timkiem']);
        Route::get('/dskhoa/{id}', [KhoaController::class, 'show']);
        Route::post('/dskhoa', [KhoaController::class, 'store']);
        Route::put('/dskhoa/{id}', [KhoaController::class, 'update']);
        Route::delete('/dskhoa/{id}', [KhoaController::class, 'destroy']);
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

