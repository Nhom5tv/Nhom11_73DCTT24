<?php

use Illuminate\Support\Facades\Route;
use App\Models\LichHoc;
use App\Models\DiemTheoLop;

use App\Models\SinhVien;
use App\Models\GiangVien;
use App\Models\Nganh;





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
    return view('pages.admin.lichhoc.dslichhoc');
});
Route::get('admin/dslichhoc/create', function () {
    return view('pages.admin.lichhoc.lichhocthem');
});
Route::get('admin/dslichhoc/{id}/edit', function ($id) {
    $LichHoc = LichHoc::find($id);
    if (!$LichHoc) {
        abort(404, 'Không tìm thấy lịch học');
    }
    return view('pages.admin.lichhoc.lichhocsua', compact('LichHoc'));
});
//Lớp Học
    Route::get('admin/dslophoc', function () {
        return view('pages.admin.lophoc.dslophoc');
    });
    Route::get('admin/dslophoc/create', function () {
        return view('pages.admin.lophoc.lophocthem');
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
//PHANH
// Giao diện danh sách sinh viên
Route::prefix('admin/sinhvien')->group(function () {
    // Danh sách sinh viên
  Route::get('/', function () {
    return view('pages.admin.qlsinhvien.index');
});
    // Giao diện tạo mới sinh viên
   Route::get('/create', function () {
    return view('pages.admin.qlsinhvien.create');
});
    // Giao diện sửa sinh viên
    Route::get('/{ma_sinh_vien}/edit', function ($ma_sinh_vien){
        $sinhvien = SinhVien::where('ma_sinh_vien', $ma_sinh_vien)->firstOrFail();
        return view('pages.admin.qlsinhvien.edit', ['sinhvien' => $sinhvien]);
    });
});
// Giao diện quản lý giảng viên (dành cho admin)
Route::prefix('admin/giangvien')->group(function () {
    // Danh sách giảng viên
    Route::get('/', function () {
        return view('pages.admin.qlgiaovien.index');
    });

    // Giao diện tạo mới giảng viên
    Route::get('/create', function (){
        return view('pages.admin.qlgiaovien.create');
    });

    // Giao diện sửa giảng viên
    Route::get('/{ma_giang_vien}/edit', function ($ma_giang_vien){
        $giangvien = GiangVien::where('ma_giang_vien', $ma_giang_vien)->firstOrFail();
        return view('pages.admin.qlgiaovien.edit', ['giangvien' => $giangvien]);
    });
});

// Giao diện quản lý ngành (dành cho admin)
Route::prefix('admin/nganh')->group(function () {
    // Danh sách ngành
    Route::get('/', function () {
        return view('pages.admin.qlnganh.index');
    });

    // Giao diện tạo mới ngành
    Route::get('/create', function () {
        return view('pages.admin.qlnganh.create');
    });

    // Giao diện sửa ngành
    Route::get('/{ma_nganh}/edit', function ($ma_nganh) {
        $nganh = Nganh::findOrFail($ma_nganh);
        return view('pages.admin.qlnganh.edit', ['nganh' => $nganh]);
    });
});