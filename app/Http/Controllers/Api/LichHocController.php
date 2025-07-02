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
    // Tìm Kiếm
    public function timkiem(Request $request)
    {
        $query = LichHoc::query();

        if ($request->filled('ma_mon_hoc')) {
            $query->where('ma_mon_hoc', 'like', '%' . $request->ma_mon_hoc . '%');
        }

        if ($request->filled('lich_hoc')) {
            $query->where('lich_hoc', 'like', '%' . $request->lich_hoc . '%');
        }

        return response()->json($query->get(), 200);
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
            'so_luong' => 'required|integer|min:0',
            'so_luong_toi_da' => 'nullable|integer|min:0',
            'lich_hoc' => 'nullable|string|max:255',
            'trang_thai' => 'nullable|string|max:255',
        ]);


        $lich = LichHoc::create($request->all());
        return response()->json($lich, 201);
    }

    // 4. Cập nhật lịch học
    public function update(Request $request, $id)
    {
        $request->validate([
            'trang_thai' => 'required|string|max:255',
        ]);

        $lichHoc = LichHoc::find($id);
        if (!$lichHoc) {
            return response()->json(['message' => 'Không tìm thấy lịch học'], 404);
        }

        $lichHoc->trang_thai = $request->trang_thai;
        $lichHoc->save();

        return response()->json(['message' => 'Cập nhật thành công'], 200);
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
    // ✅ Sửa hàm này lại như sau:
        public function dongTatCa()
        {
            $soDong = LichHoc::where('trang_thai', 'Đang Mở')->update([
                'trang_thai' => 'Đóng'
            ]);

            return response()->json([
                'message' => "Đã đóng $soDong lớp học.",
                'count' => $soDong
            ]);
        }
}
