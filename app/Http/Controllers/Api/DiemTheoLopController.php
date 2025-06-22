<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiemTheoLop;

class DiemTheoLopController extends Controller
{
    // Lấy tất cả điểm theo lớp
    public function getData()
    {
        try {
            $data = DiemTheoLop::all();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Cập nhật điểm
    public function updateData(Request $request, $id)
    {
        $validated = $request->validate([
            //'id' => 'required|integer',
            'diem_chuyen_can' => 'required|numeric',
            'diem_giua_ky' => 'required|numeric',
            'diem_cuoi_ky' => 'required|numeric',
        ]);

        $diem = DiemTheoLop::find($id);
        if ($diem) {
            // Cập nhật các trường dữ liệu
            $diem->diem_chuyen_can = $validated['diem_chuyen_can'];
            $diem->diem_giua_ky = $validated['diem_giua_ky'];
            $diem->diem_cuoi_ky = $validated['diem_cuoi_ky'];
            $diem->save();

            return response()->json(['message' => 'Cập nhật điểm thành công!']);
        }
        return response()->json(['message' => 'Không tìm thấy điểm'], 404);
    }
}
