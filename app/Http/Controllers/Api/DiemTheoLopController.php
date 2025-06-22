<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiemTheoLop;

class DiemTheoLopController extends Controller
{
    // Lấy tất cả điểm theo lớp
    public function getData($ma_lop)
    {
        try {
            // Lấy các bản ghi có ma_lop tương ứng
            $data = DiemTheoLop::where('ma_lop', $ma_lop)->get();

            // Trả về dữ liệu dưới dạng JSON
            return response()->json($data);
        } catch (\Exception $e) {
            // Nếu có lỗi, trả về thông báo lỗi với mã lỗi 500
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // Cập nhật điểm
    public function updateData(Request $request, $ma_sinh_vien)
    {
        $validated = $request->validate([
            'ma_lop' => 'required|integer',
            'diem_chuyen_can' => 'required|numeric|min:0|max:10',
            'diem_giua_ky' => 'required|numeric|min:0|max:10',
            'diem_cuoi_ky' => 'required|numeric|min:0|max:10',
        ]);

        // Tìm bản ghi điểm theo mã sinh viên và mã lớp
        $diem = DiemTheoLop::where('ma_sinh_vien', $ma_sinh_vien)
            ->where('ma_lop', $validated['ma_lop'])
            ->first();

        if ($diem) {
            $diem->update([
                'diem_chuyen_can' => $validated['diem_chuyen_can'],
                'diem_giua_ky' => $validated['diem_giua_ky'],
                'diem_cuoi_ky' => $validated['diem_cuoi_ky']
            ]);

            return response()->json([
                'message' => 'Cập nhật điểm thành công!',
                'data' => $diem
            ]);
        }

        return response()->json([
            'message' => 'Không tìm thấy điểm của sinh viên trong lớp này'
        ], 404);
    }
}
