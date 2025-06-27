<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Models\LichHoc;
use App\Models\LopHoc;
use Illuminate\Support\Facades\Log;
use App\Models\DiemTheoLop;

use App\Models\SinhVien;
use App\Models\GiangVien;
use App\Models\DangKyMonHoc;
use App\Models\Nganh;





Route::get('/', function () {
    return view('welcome');
});

Route::view('/login', 'login');
Route::get('/giaovien', function () {
    return 'Đây là trang dành cho giáo viên (tạm thời)';
});

//Đây là phần của Dũng
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
    Route::get('admin/dslophoc/{id}/edit', function ($id) {
    $lopHoc = LopHoc::find($id);
    if (!$lopHoc) {
        Log::error("Không tìm thấy lớp học với ID: " . $id);
        abort(404, 'Không tìm thấy lịch học');
    }
    return view('pages.admin.lophoc.lophocsua', compact('lopHoc'));
});
//Đăng ký môn học
    Route::get('admin/dkmonhoc', function () {
        return view('pages.admin.dang_ky_mon_hoc.dkmonhoc');
    });
//Sinh Viên Đăng Ký Tín Chỉ
    Route::get('sinhvien/dktinchi', function () {
        return view('pages.sinhvien.dktinchi');
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
//Đổi mật khẩu 
Route::get('/change-password', function() {
    return view('change_password');
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
Route::get('/admin/khoanthu/create', function () {
    return view('pages.admin.khoan_thu.create');
});
Route::get('/admin/khoanthu/{id}/edit', function ($id) {
    return view('pages.admin.khoan_thu.edit', ['id' => $id]);
});

// Quản lý khoản thu sinh viên
Route::get('/admin/khoanthusv', function () {
    return view('pages.admin.khoan_thu_sinh_vien.index');
});
// Quản lý hóa đơn
Route::get('/admin/hoadon', function () {
    return view('pages.admin.hoa_don.index');
});
Route::get('/admin/hoadon/create', function () {
    return view('pages.admin.hoa_don.create');
});
//Hóa đơn bên sinh viên
Route::get('/sinhvien/hoadon', function () {
    return view('pages.sinhvien.taichinh');
});
//Hết phần của Quỳnh

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

Route::get('sinhvien/diem', function () {
    return view('pages.sinhvien.DiemSinhVien.index');
});
// Trang danh sách các khoa
Route::prefix('admin')->group(function () {
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



// Route cho trang chi tiết điểm
Route::get('sinhvien/diem-chi-tiet', function () {
    return view('pages.sinhvien.DiemSinhVien.chitiet');
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
    Route::get('/create', function () {
        return view('pages.admin.qlgiaovien.create');
    });

    // Giao diện sửa giảng viên
    Route::get('/{ma_giang_vien}/edit', function ($ma_giang_vien) {
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
// Route trang chủ của sinh viên (nếu có layout sinh viên riêng)
Route::get('/sinhvien', function () {
    return view('layout'); // hoặc 'layout_sinhvien' nếu bạn tách layout theo role
});

// Trang thông tin sinh viên
Route::get('/sinhvien/thongtinsv', function () {
    return view('pages.sinhvien.thongtinsv');
});
// Giao diện chỉnh sửa thông tin sinh viên cá nhân (sinh viên đang đăng nhập)
Route::get('/sinhvien/thongtinsv/edit/{ma_sinh_vien}', function ($ma_sinh_vien) {
    $sinhvien = SinhVien::where('ma_sinh_vien', $ma_sinh_vien)->firstOrFail();
    return view('pages.sinhvien.thongtinsv_edit', ['sinhvien' => $sinhvien]);
});
//thong tin gv

Route::get('/giaovien/thongtingv', function () {
    return view('pages.giaovien.thongtingv');
});
// Giao diện chỉnh sửa thông tin giảng viên cá nhân (giảng viên đang đăng nhập)
Route::get('/giaovien/thongtingv/edit', function () {
    return view('pages.giaovien.thongtingv_edit');
});
//giao diện thống kê
Route::get('/admin/thongke', function () {
    return view('pages.admin.thongke.index');
});