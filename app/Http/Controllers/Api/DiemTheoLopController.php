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
            $data = DiemTheoLop::with('sinhVien') // Eager load sinh viên
            ->where('ma_lop', $ma_lop)
            ->get();

        // Nếu không có dữ liệu
        if ($data->isEmpty()) {
            return response()->json(['message' => 'Không có điểm cho lớp học này'], 404);
        }

            // Trả về dữ liệu dưới dạng JSON
            return response()->json($data);
        } catch (\Exception $e) {
            // Nếu có lỗi, trả về thông báo lỗi với mã lỗi 500
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // Cập nhật điểm
    // public function updateData(Request $request, $ma_sinh_vien)
    // {
    //     $validated = $request->validate([
    //         'ma_lop' => 'required|integer',
    //         'diem_chuyen_can' => 'required|numeric|min:0|max:10',
    //         'diem_giua_ky' => 'required|numeric|min:0|max:10',
    //         'diem_cuoi_ky' => 'required|numeric|min:0|max:10',
    //     ]);

    //     // Tìm bản ghi điểm theo mã sinh viên và mã lớp
    //     $diem = DiemTheoLop::where('ma_sinh_vien', $ma_sinh_vien)
    //         ->where('ma_lop', $validated['ma_lop'])
    //         ->first();

    //     if ($diem) {
    //         $diem->update([
    //             'diem_chuyen_can' => $validated['diem_chuyen_can'],
    //             'diem_giua_ky' => $validated['diem_giua_ky'],
    //             'diem_cuoi_ky' => $validated['diem_cuoi_ky']
    //         ]);

    //         return response()->json([
    //             'message' => 'Cập nhật điểm thành công!',
    //             'data' => $diem
    //         ]);
    //     }

    //     return response()->json([
    //         'message' => 'Không tìm thấy điểm của sinh viên trong lớp này'
    //     ], 404);
    // }
    public function updateData(Request $request, $ma_sinh_vien)
{
    $validated = $request->validate([
        'ma_lop' => 'required|integer',
        'diem_chuyen_can' => 'nullable|numeric|min:0|max:10',
        'diem_giua_ky' => 'nullable|numeric|min:0|max:10',
        'diem_cuoi_ky' => 'nullable|numeric|min:0|max:10',
    ]);

    $diem = DiemTheoLop::where('ma_sinh_vien', $ma_sinh_vien)
        ->where('ma_lop', $validated['ma_lop'])
        ->first();

    if (!$diem) {
        return response()->json([
            'message' => 'Không tìm thấy điểm của sinh viên trong lớp này'
        ], 404);
    }

    // Tạo mảng chỉ chứa các trường được gửi lên
    $fieldsToUpdate = [];
    if ($request->has('diem_chuyen_can')) $fieldsToUpdate['diem_chuyen_can'] = $validated['diem_chuyen_can'];
    if ($request->has('diem_giua_ky')) $fieldsToUpdate['diem_giua_ky'] = $validated['diem_giua_ky'];
    if ($request->has('diem_cuoi_ky')) $fieldsToUpdate['diem_cuoi_ky'] = $validated['diem_cuoi_ky'];

    $diem->update($fieldsToUpdate);

    return response()->json([
        'message' => 'Cập nhật điểm thành công!',
        'data' => $diem
    ]);
}

}
