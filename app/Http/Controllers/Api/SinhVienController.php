<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use App\Models\SinhVien;

class SinhVienController extends Controller
{
    /**
     * Hiển thị danh sách sinh viên.
     */
    public function index()
    {
        return SinhVien::all();
    }

    /**
     * Thêm mới một sinh viên.
     */
   public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'ma_sinh_vien' => 'required|string|max:10|unique:sinh_vien',
            'user_id' => 'required|integer', 
            'ma_khoa' => 'required|integer',
            'ma_nganh' => 'required|integer',
            'ho_ten' => 'required|string|max:100',
            'ngay_sinh' => 'required|date',
            'gioi_tinh' => 'required|in:Nam,Nữ',
            'que_quan' => 'required|string|max:100',
            'email' => 'required|email|unique:sinh_vien',
            'so_dien_thoai' => 'required|string|max:15',
            'khoa_hoc' => 'required|integer',
        ]);

        $sinhvien = SinhVien::create($validated);
        return response()->json($sinhvien, 201);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Đã xảy ra lỗi',
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            // 'trace' => $e->getTraceAsString() // nếu muốn debug sâu hơn
        ], 500);
    }
}


    /**
     * Lấy thông tin chi tiết 1 sinh viên.
     */
    public function show(string $id)
    {
        $sinhvien = SinhVien::findOrFail($id);
        return response()->json($sinhvien);
    }

    /**
     * Cập nhật thông tin sinh viên.
     */
    public function update(Request $request, string $id)
    {
        $sinhvien = SinhVien::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'ma_khoa' => 'required|integer',
            'ma_nganh' => 'required|integer',
            'ho_ten' => 'required|string|max:100',
            'ngay_sinh' => 'required|date',
            'gioi_tinh' => 'required|in:Nam,Nữ',
            'que_quan' => 'required|string|max:100',
            'email' => 'required|email|unique:sinh_vien,email,' . $id . ',ma_sinh_vien',
            'so_dien_thoai' => 'required|string|max:15',
            'khoa_hoc' => 'required|integer',
        ]);

        $sinhvien->update($validated);
        return response()->json($sinhvien);
    }

    /**
     * Xóa một sinh viên.
     */
    public function destroy(string $id)
    {
        $sinhvien = SinhVien::findOrFail($id);
        $sinhvien->delete();
        return response()->json(['message' => 'Đã xóa sinh viên thành công']);
    }
}
