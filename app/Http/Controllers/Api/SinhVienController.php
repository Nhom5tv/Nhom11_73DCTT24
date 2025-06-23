<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use App\Models\SinhVien;

class SinhVienController extends Controller
{
    // ✅ Trang danh sách sinh viên (web + API)
    public function index(Request $request)
    {
        $query = SinhVien::query();

        if ($request->has('ma_sinh_vien')) {
            $query->where('ma_sinh_vien', 'like', '%' . $request->ma_sinh_vien . '%');
        }

        if ($request->has('ho_ten')) {
            $query->where('ho_ten', 'like', '%' . $request->ho_ten . '%');
        }

        $sinhviens = $query->get();

        // Nếu là API request
        if ($request->wantsJson()) {
            return response()->json($sinhviens);
        }

        // Nếu là web (blade)
        return view('sinhvien.index', compact('sinhviens'));
    }

    // ✅ Giao diện tạo sinh viên (Web)
    public function create()
    {
        return view('sinhvien.create');
    }

    // ✅ Thêm sinh viên (Web + API)
    public function store(Request $request)
    {
        // Xác định request có phải từ API không
        $isApi = $request->wantsJson();

        $validated = $request->validate([
            'ma_sinh_vien' => $isApi ? 'required|string|max:10|unique:sinh_vien' : 'required|string|max:10|unique:sinh_vien,ma_sinh_vien',
            'user_id' => 'required|exists:users,id',
            'ma_khoa' => 'required|integer',
            'ma_nganh' => 'required|integer',
            'ho_ten' => 'required|string|max:100',
            'ngay_sinh' => 'required|date',
            'gioi_tinh' => 'required|in:Nam,Nữ',
            'que_quan' => 'required|string|max:100',
            'email' => $isApi ? 'required|email|unique:sinh_vien' : 'required|email|unique:sinh_vien,email',
            'so_dien_thoai' => 'required|string|max:15',
            'khoa_hoc' => 'required|integer',
        ]);

        $sinhvien = SinhVien::create($validated);

        if ($isApi) {
            return response()->json($sinhvien, 201);
        }

        return redirect()->back()->with('success', 'Thêm sinh viên thành công!');
    }

    // ✅ Xem chi tiết sinh viên (API)
    public function show($ma_sinh_vien)
    {
        $sv = SinhVien::where('ma_sinh_vien', $ma_sinh_vien)->firstOrFail();
        return response()->json($sv);
    }

    // ✅ Giao diện sửa sinh viên (Web)
    public function edit($ma_sinh_vien)
    {
        $sinhvien = SinhVien::where('ma_sinh_vien', $ma_sinh_vien)->firstOrFail();
        return view('sinhvien.edit', compact('sinhvien'));
    }

    // ✅ Cập nhật sinh viên (Web + API)
    public function update(Request $request, $ma_sinh_vien)
    {
        $sv = SinhVien::where('ma_sinh_vien', $ma_sinh_vien)->firstOrFail();
        $isApi = $request->wantsJson();

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'ma_khoa' => 'required|integer',
            'ma_nganh' => 'required|integer',
            'ho_ten' => 'required|string|max:100',
            'ngay_sinh' => 'required|date',
            'gioi_tinh' => 'required|in:Nam,Nữ',
            'que_quan' => 'required|string|max:100',
            'email' => 'required|email|unique:sinh_vien,email,' . $sv->ma_sinh_vien . ',ma_sinh_vien',
            'so_dien_thoai' => 'required|string|max:15',
            'khoa_hoc' => 'required|integer',
        ]);

        $sv->update($validated);

        if ($isApi) {
            return response()->json($sv);
        }

        return redirect()->route('sinhvien.index')->with('success', 'Cập nhật sinh viên thành công!');
    }

    // ✅ Xóa sinh viên (Web + API)
    public function destroy($ma_sinh_vien)
    {
        $sinhvien = SinhVien::where('ma_sinh_vien', $ma_sinh_vien)->firstOrFail();
        $sinhvien->delete();

        // Xác định kiểu response
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Đã xóa sinh viên thành công']);
        }

        return redirect()->route('sinhvien.index')->with('success', 'Đã xóa sinh viên');
    }
}
