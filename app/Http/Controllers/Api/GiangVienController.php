<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use App\Models\GiangVien;

class GiangVienController extends Controller
{
    // ✅ Danh sách giảng viên (Web + API)
    public function index(Request $request)
    {
        $query = GiangVien::query();

        if ($request->has('ma_giang_vien')) {
            $query->where('ma_giang_vien', 'like', '%' . $request->ma_giang_vien . '%');
        }

        if ($request->has('ho_ten')) {
            $query->where('ho_ten', 'like', '%' . $request->ho_ten . '%');
        }

        $giangviens = $query->get();

        if ($request->wantsJson()) {
            return response()->json($giangviens);
        }

        return view('giangvien.index', compact('giangviens'));
    }

    // ✅ Giao diện thêm (Web)
    public function create()
    {
        return view('giangvien.create');
    }

    // ✅ Thêm giảng viên (Web + API)
    public function store(Request $request)
    {
        $isApi = $request->wantsJson();

        $validated = $request->validate([
            'ma_giang_vien' => 'required|string|max:10|unique:giang_vien',
            'user_id' => 'required|exists:users,id',
            'ma_khoa' => 'required|integer',
            'ho_ten' => 'required|string|max:50',
            'email' => 'required|email|unique:giang_vien',
            'so_dien_thoai' => 'nullable|string|max:15',
            'chuyen_nganh' => 'nullable|string|max:50',
        ]);

        $gv = GiangVien::create($validated);

        if ($isApi) {
            return response()->json($gv, 201);
        }

        return redirect()->back()->with('success', 'Thêm giảng viên thành công!');
    }

    // ✅ Xem chi tiết (API)
    public function show($ma_giang_vien)
    {
        $gv = GiangVien::where('ma_giang_vien', $ma_giang_vien)->firstOrFail();
        return response()->json($gv);
    }

    // ✅ Giao diện sửa (Web)
    public function edit($ma_giang_vien)
    {
        $giangvien = GiangVien::where('ma_giang_vien', $ma_giang_vien)->firstOrFail();
        return view('giangvien.edit', compact('giangvien'));
    }

    // ✅ Cập nhật giảng viên (Web + API)
    public function update(Request $request, $ma_giang_vien)
    {
        $gv = GiangVien::where('ma_giang_vien', $ma_giang_vien)->firstOrFail();
        $isApi = $request->wantsJson();

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'ma_khoa' => 'required|integer',
            'ho_ten' => 'required|string|max:50',
            'email' => 'required|email|unique:giang_vien,email,' . $gv->ma_giang_vien . ',ma_giang_vien',
            'so_dien_thoai' => 'nullable|string|max:15',
            'chuyen_nganh' => 'nullable|string|max:50',
        ]);

        $gv->update($validated);

        if ($isApi) {
            return response()->json($gv);
        }

        return redirect()->route('giangvien.index')->with('success', 'Cập nhật giảng viên thành công!');
    }

    // ✅ Xóa giảng viên (Web + API)
    public function destroy($ma_giang_vien)
    {
        $gv = GiangVien::where('ma_giang_vien', $ma_giang_vien)->firstOrFail();
        $gv->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Đã xóa giảng viên']);
        }

        return redirect()->route('giangvien.index')->with('success', 'Đã xóa giảng viên');
    }
}
