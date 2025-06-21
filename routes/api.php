<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
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

Route::apiResource('/admin/monhoc', MonHocController::class);
?>