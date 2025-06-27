<?php

use App\Http\Controllers\Api\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KhoanThuController;
use App\Http\Controllers\Api\MienGiamSinhVienController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Api\DiemTheoLopController;
use App\Http\Controllers\Api\LichHocController;
use App\Http\Controllers\Api\LopHocController;
use App\Http\Controllers\Api\MonHocController;
use App\Http\Controllers\Api\TaiKhoanController;
use App\Http\Controllers\Api\SinhVienController;
use App\Http\Controllers\Api\GiangVienController;
use App\Http\Controllers\Api\NganhController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DangKyMonHocController;
use App\Http\Controllers\Api\DangKyTinChiController;
use App\Http\Controllers\Api\HoaDonController;
use App\Http\Controllers\Api\KhoanThuSinhVienController;
use App\Http\Controllers\Api\DiemSinhVienController;




Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->get('/me', [AuthController::class, 'me']);
Route::middleware(['auth:api', RoleMiddleware::class . ':admin'])->get('/admin', function () {
    return response()->json(['msg' => 'Chào Admin']);
});



Route::middleware(['auth:api', RoleMiddleware::class . ':sinhvien'])->get('/sinhvien', function () {
    return response()->json(['msg' => 'Chào Sinh viên']);
});
//phan cua Vu
//API routes for MonHoc
Route::prefix('admin')->middleware('auth:api')->group(function () {
    Route::get('/monhoc', [MonHocController::class, 'index']);
    Route::post('/monhoc', [MonHocController::class, 'store']);
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

// Route::prefix('sinhvien')->middleware(['auth:api', RoleMiddleware::class . ':sinhvien'])->group(function () {
//     Route::get('diem-chi-tiet/{ma_sinh_vien}', [DiemSinhVienController::class, 'getDiem']);
// });
Route::prefix('sinhvien')->middleware(['auth:api', RoleMiddleware::class . ':sinhvien'])->group(function () {
    // Sửa tên method cho đúng
    Route::get('diem-chi-tiet/{ma_sinh_vien}', [DiemSinhVienController::class, 'getDiem']);
    
    // Hoặc nếu muốn tách riêng 2 endpoint
    Route::get('diem/{ma_sinh_vien}', [DiemSinhVienController::class, 'getDiem']);
    Route::get('diem-chi-tiet/{ma_sinh_vien}', [DiemSinhVienController::class, 'getDiemChiTiet']);
});

//Phần của Dũng

Route::prefix('admin')->middleware(['auth:api', RoleMiddleware::class . ':admin'])->group(function () {
//Chức năng quản lý lịch học
    // 1. Lấy tất cả lịch học
    Route::get('/dslichhoc', [LichHocController::class, 'index']);
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

    //Chức năng Đăng ký môn học
    Route::get('/dkmonhoc', [DangKyMonHocController::class, 'index']);
    Route::get('/dkmonhoc/{id}', [DangKyMonHocController::class, 'show']);
    Route::post('/dkmonhoc', [DangKyMonHocController::class, 'store']);
    Route::put('/dkmonhoc/{id}', [DangKyMonHocController::class, 'update']);
    Route::delete('/dkmonhoc/{id}', [DangKyMonHocController::class, 'destroy']);
    Route::post('/dangky/huytatca', [DangKyMonHocController::class, 'huyTatCa']);
});

    //Chức năng Đăng Ký tín chỉ
    Route::middleware(['auth:api'])->prefix('sinhvien')->group(function () {
    Route::get('/monhoc', [DangKyTinChiController::class, 'monHocCoTheDangKy']);
    Route::post('/monhoc', [DangKyTinChiController::class, 'dangKyMonHoc']);
    Route::get('/dadangky', [DangKyTinChiController::class, 'dsDaDangKy']);
    Route::delete('/huydangky/{id}', [DangKyTinChiController::class, 'huyDangKyMonHoc']);
});


//hết phần của Dũng


Route::apiResource('/admin/monhoc', MonHocController::class);

// Api Quỳnh
//Đổi mật khẩu khi đăng nhập lần đầu /ấn đổi mật khẩu
Route::middleware('auth:api')->post('/change-password', [UserController::class, 'changePassword']);
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
//Quản lý khoản thu sinh viên
Route::prefix('admin')->middleware('auth:api')->group(function () {
    Route::get('/khoanthusv', [KhoanThuSinhVienController::class, 'index']);
});
//Quản lý hóa đơn
Route::prefix('admin')->middleware('auth:api')->group(function () {
    Route::get('/hoadon', [HoaDonController::class, 'index']);
    Route::post('/hoadon', [HoaDonController::class, 'store']);
    Route::put('/hoadon/{id}', [HoaDonController::class, 'cancel']);
});

//Hóa đơn bên sinh viên
Route::prefix('sinhvien')->middleware('auth:api')->group(function () {
    Route::get('/hoadon', [HoaDonController::class, 'getHoaDon']);
    Route::get('/hoadon/khoannop', [HoaDonController::class, 'getKhoanPhaiNop']);
    
});
// Hết phần của Quỳnh

Route::prefix('admin')->middleware(['auth:api', RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/sinhvien', [SinhVienController::class, 'index']);
    Route::post('/sinhvien', [SinhVienController::class, 'store']);
    Route::get('/sinhvien/{ma_sinh_vien}', [SinhVienController::class, 'show']);
    Route::put('/sinhvien/{ma_sinh_vien}', [SinhVienController::class, 'update']);
    Route::delete('/sinhvien/{ma_sinh_vien}', [SinhVienController::class, 'destroy']);
});
Route::prefix('admin')->middleware(['auth:api', RoleMiddleware::class . ':admin'])->group(function () {
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

});Route::middleware(['auth:api', RoleMiddleware::class . ':sinhvien'])->group(function () {
    Route::get('/thongtinsv', [SinhVienController::class, 'getThongTinCaNhan']);
    Route::put('/thongtinsv', [SinhVienController::class, 'capNhatThongTinCaNhan']); // 👈 gọi hàm riêng
});
Route::middleware(['auth:api', RoleMiddleware::class . ':giaovien'])->group(function () {
    Route::get('/thongtingv', [GiangVienController::class, 'getThongTinGiangVien']);
        Route::put('/thongtingv', [GiangVienController::class, 'updateThongTinGiangVien']);

});





//hết phần của PA
