<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KhoanThu;
use Illuminate\Http\Request;
use Exception;

class KhoanThuController extends Controller
{
    public function index(Request $request)
{
    $query = KhoanThu::query();

    if ($request->filled('ten_khoan_thu')) {
        $query->where('ten_khoan_thu', 'like', '%' . $request->ten_khoan_thu . '%');
    }

    if ($request->filled('from_date')) {
        $query->whereDate('han_nop', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('han_nop', '<=', $request->to_date);
    }

    return response()->json($query->orderByDesc('ma_khoan_thu')->get());
}


    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'ten_khoan_thu' => 'required|string|max:255',
                'loai_khoan_thu' => 'required|in:Học phí,BHYT,Khác',
                'so_tien' => 'required|numeric|min:0',
                'ngay_tao' => 'nullable|date',
                'han_nop' => 'nullable|date'
            ]);

            $khoanThu = KhoanThu::create($validated);
            return response()->json(['message' => 'Tạo khoản thu thành công', 'data' => $khoanThu]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $item = KhoanThu::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'ten_khoan_thu' => 'required|string|max:255',
                'loai_khoan_thu' => 'required|in:Học phí,BHYT,Khác',
                'so_tien' => 'required|numeric|min:0',
                'ngay_tao' => 'nullable|date',
                'han_nop' => 'nullable|date'
            ]);

            $item = KhoanThu::findOrFail($id);
            $item->update($validated);

            return response()->json(['message' => 'Cập nhật thành công', 'data' => $item]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $item = KhoanThu::findOrFail($id);
        $item->delete();
        return response()->json(['message' => 'Xóa thành công']);
    }
}
