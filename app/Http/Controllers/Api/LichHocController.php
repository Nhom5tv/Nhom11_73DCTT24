<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LichHoc;
use Illuminate\Http\Request;

class LichHocController extends Controller
{
    // 1. Lấy toàn bộ lịch học
    public function index()
    {
        return response()->json(LichHoc::all());
    }

    // 2. Lấy 1 lịch học theo ID
    public function show($id)
    {
        $lich = LichHoc::find($id);
        if (!$lich) {
            return response()->json(['message' => 'Không tìm thấy'], 404);
        }
        return response()->json($lich);
    }

    // 3. Tạo mới lịch học
    public function store(Request $request)
    {
        $request->validate([
            'ma_mon_hoc' => 'required|string|max:10',
            'so_luong' => 'required|integer|min:1',
            'so_luong_toi_da' => 'nullable|integer|min:1',
            'lich_hoc' => 'nullable|string|max:255',
            'trang_thai' => 'nullable|string|max:255',
        ]);

        $lich = LichHoc::create($request->all());
        return response()->json($lich, 201);
    }

    // 4. Cập nhật lịch học
    public function update(Request $request, $id)
    {
        $lich = LichHoc::find($id);
        if (!$lich) {
            return response()->json(['message' => 'Không tìm thấy'], 404);
        }

        $request->validate([
            'ma_mon_hoc' => 'sometimes|required|string|max:10',
            'so_luong' => 'sometimes|required|integer|min:1',
            'so_luong_toi_da' => 'nullable|integer|min:1',
            'lich_hoc' => 'nullable|string|max:255',
            'trang_thai' => 'nullable|string|max:255',
        ]);

        $lich->update($request->all());
        return response()->json($lich);
    }

    // 5. Xoá lịch học
    public function destroy($id)
    {
        $lich = LichHoc::find($id);
        if (!$lich) {
            return response()->json(['message' => 'Không tìm thấy'], 404);
        }

        $lich->delete();
        return response()->json(['message' => 'Đã xoá thành công']);
    }
    // 6.Đóng tất cả
    public function dongTatCa(Request $request)
{
    $soDong = LichHoc::where('trang_thai', 'Đang Mở')->update(['trang_thai' => 'Đóng']);

    return response()->json([
        'message' => "Đã đóng $soDong lớp học.",
        'count' => $soDong
    ]);
}

}
?>
