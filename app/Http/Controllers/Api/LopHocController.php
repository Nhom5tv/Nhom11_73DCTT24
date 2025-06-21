<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LopHoc;
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
            'ma_lop' => 'required|integer|unique:lop,ma_lop',
            'ma_mon' => 'required|string|max:10',
            'hoc_ky' => 'required|string|max:9',
            'ma_giang_vien' => 'required|string|max:10',
            'lich_hoc' => 'nullable|string|max:255',
            'trang_thai' => 'required|string|max:255',
        ]);

        $lop = LopHoc::create($request->all());
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
}
