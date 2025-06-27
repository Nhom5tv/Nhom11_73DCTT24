<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KhoanThuSinhVien;
use App\Models\KhoanThu;

class KhoanThuSinhVienController extends Controller
{
    public function index(Request $request)
    {
        $query = KhoanThuSinhVien::query()
            ->join('khoan_thu', 'khoan_thu.ma_khoan_thu', '=', 'khoan_thu_sinh_vien.ma_khoan_thu')
            ->select(
                'khoan_thu_sinh_vien.*',
                'khoan_thu.ten_khoan_thu'
            );

        // Lọc theo mã sinh viên
        if ($request->filled('ma_sinh_vien')) {
            $query->where('khoan_thu_sinh_vien.ma_sinh_vien', 'like', '%' . $request->ma_sinh_vien . '%');
        }

        // Lọc theo tên khoản thu
        if ($request->filled('ten_khoan_thu')) {
            $query->where('khoan_thu.ten_khoan_thu', 'like', '%' . $request->ten_khoan_thu . '%');
        }

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('khoan_thu_sinh_vien.trang_thai_thanh_toan', $request->trang_thai);
        }

        $ds = $query->orderByDesc('khoan_thu_sinh_vien.ma_khoan_thu')->get();

        return response()->json($ds);
    }
}
