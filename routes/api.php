<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MienGiamSinhVienController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Api\MonHocController;

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