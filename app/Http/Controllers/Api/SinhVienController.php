<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use App\Models\SinhVien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
    DB::beginTransaction(); // Bắt đầu transaction

    try {
        $validated = $request->validate([
            'ma_sinh_vien' => 'required|string|max:10|unique:sinh_vien',
            'ma_khoa' => 'required|integer',
            'ma_nganh' => 'required|integer',
            'ho_ten' => 'required|string|max:100',
            'ngay_sinh' => 'required|date',
            'gioi_tinh' => 'required|in:Nam,Nữ',
            'que_quan' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'so_dien_thoai' => 'required|string|max:15',
            'khoa_hoc' => 'required|integer',
        ]);

        // 1. Tạo tài khoản user
        $user = \App\Models\User::create([
            'name' => $validated['ho_ten'],
            'email' => $validated['email'],
            'password' => Hash::make('12345678'),
            'role' => 'sinhvien',
            'must_change_password' => true
        ]);

        // 2. Tạo sinh viên
        $sinhvien = SinhVien::create([
            'ma_sinh_vien' => $validated['ma_sinh_vien'],
            'user_id' => $user->id,
            'ma_khoa' => $validated['ma_khoa'],
            'ma_nganh' => $validated['ma_nganh'],
            'ho_ten' => $validated['ho_ten'],
            'ngay_sinh' => $validated['ngay_sinh'],
            'gioi_tinh' => $validated['gioi_tinh'],
            'que_quan' => $validated['que_quan'],
            'email' => $validated['email'],
            'so_dien_thoai' => $validated['so_dien_thoai'],
            'khoa_hoc' => $validated['khoa_hoc'],
        ]);

        DB::commit(); // Thành công: xác nhận ghi vào DB

        return response()->json([
            'message' => 'Tạo sinh viên và tài khoản thành công',
            'sinhvien' => $sinhvien,
            'user' => $user
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack(); // Có lỗi: rollback tất cả

        return response()->json([
            'message' => 'Đã xảy ra lỗi, hệ thống đã rollback',
            'error' => $e->getMessage(),
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
