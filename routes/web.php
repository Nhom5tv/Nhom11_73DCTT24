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

// Trang danh sách các khoa
Route::prefix('admin')->group(function () {
    Route::get('/khoa', function () {
        return view('pages.khoa.index');
    })->name('admin.khoa.index');
    Route::get('/khoa', function () {
        return view('pages.admin.khoa.index');
    })->name('khoa.index');

    // Trang thêm khoa mới
    Route::get('/khoa/tao-moi', function () {
        return view('pages.admin.khoa.create');
    })->name('khoa.create');

    // Trang chỉnh sửa một khoa cụ thể
    Route::get('/khoa/sua/{ma_khoa}', function ($ma_khoa) {
        return view('pages.admin.khoa.edit', compact('ma_khoa'));
    })->name('khoa.edit');
});



// Route::get('/admin/qlgiaovien', function () {
//     return view('pages.admin.qlgiaovien.index');
// })->name('qlgiaovien.index');

// // View form thêm mới
// Route::get('/admin/giangvien/them', function () {
//     return view('pages.admin.qlgiaovien.them');
// })->name('giangvien.create');

// // View form sửa giảng viên
// Route::get('/admin/qlgiaovien/sua/{ma_gv}', function ($ma_gv) {
//     return view('pages.admin.qlgiaovien.sua', ['ma_gv' => $ma_gv]);
// })->name('qlgiaovien.edit');


Route::get('/admin/monhoc', function () {
    return view('pages.admin.monhoc');
});
