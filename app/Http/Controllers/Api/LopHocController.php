<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LopHoc;
use App\Models\DangKyMonHoc;
//đạt
use App\Models\DiemTheoLop;
use App\Models\SinhVien;

class LopHocController extends Controller
{
    // 1. Lấy tất cả lớp học
    public function index()
    {
        return response()->json(LopHoc::all());
    }

    // 2. Lấy thông tin lớp học theo mã lớp
    public function show($id)
    {
        $lop = LopHoc::find($id);
        if (!$lop) {
            return response()->json(['message' => 'Không tìm thấy lớp học'], 404);
        }
        return response()->json($lop);
    }

    // 3. Thêm mới lớp học
    public function store(Request $request)
    {
        $request->validate([
            'ma_mon' => 'required|string|max:10',
            'hoc_ky' => 'required|string|max:9',
            'ma_giang_vien' => 'required|string|max:10',
            'lich_hoc' => 'nullable|string|max:255',
            'trang_thai' => 'required|string|max:255',
        ]);

        $lop = LopHoc::create($request->all());

        // Gán ma_lop vào các đăng ký chưa có lớp
        DangKyMonHoc::where('ma_mon', $lop->ma_mon)
            ->whereNull('ma_lop')
            ->update(['ma_lop' => $lop->ma_lop]);

        // Sau khi gán ma_lop, cần cập nhật lần học cho các sinh viên đã đăng ký
        // Xử lý điểm cho sinh viên
    $dangKys = DangKyMonHoc::with('sinhVien')
        ->where('ma_mon', $lop->ma_mon)
        ->where('ma_lop', $lop->ma_lop)
        ->get();

    foreach ($dangKys as $dangKy) {
        // Tính lần học (không lưu ma_mon vì model không có trường này)
        $lanHoc = DiemTheoLop::where('ma_sinh_vien', $dangKy->ma_sinh_vien)
            ->where('ma_lop', $lop->ma_lop)
            ->max('lan_hoc') ?? 0;
        
        DiemTheoLop::create([
            'ma_lop' => $lop->ma_lop,
            'ma_sinh_vien' => $dangKy->ma_sinh_vien,
            'ten_sinh_vien' => $dangKy->sinhVien->ten_sinh_vien ?? 'Không rõ',
            'lan_hoc' => $lanHoc + 1,
            'diem_chuyen_can' => null,
            'diem_giua_ky' => null,
            'diem_cuoi_ky' => null
        ]);
    }

        return response()->json($lop, 201);
    }


    // 4. Cập nhật lớp học theo mã lớp
    public function update(Request $request, $id)
    {
        $lop = LopHoc::find($id);
        if (!$lop) {
            return response()->json(['message' => 'Không tìm thấy lớp học'], 404);
        }

        $request->validate([
            'ma_mon' => 'sometimes|string|max:10',
            'hoc_ky' => 'sometimes|string|max:9',
            'ma_giang_vien' => 'sometimes|string|max:10',
            'lich_hoc' => 'nullable|string|max:255',
            'trang_thai' => 'required|string|max:255',
        ]);

        $lop->update($request->all());
        return response()->json($lop);
    }

    // 5. Xóa lớp học theo mã lớp
    public function destroy($id)
    {
        $lop = LopHoc::find($id);
        if (!$lop) {
            return response()->json(['message' => 'Không tìm thấy lớp học'], 404);
        }

        $lop->delete();
        return response()->json(['message' => 'Đã xóa lớp học thành công']);
    }

    //Đạt
    //6. Lấy mã lớp theo MãGV
    public function getByMaGiangVien($ma_giang_vien)
    {
        $lop = LopHoc::where('ma_giang_vien', $ma_giang_vien)->get();
        if ($lop->isEmpty()) {
            return response()->json(['message' => 'Không tìm thấy lớp học nào cho giảng viên này'], 404);
        }
        return response()->json($lop);
    }
}
