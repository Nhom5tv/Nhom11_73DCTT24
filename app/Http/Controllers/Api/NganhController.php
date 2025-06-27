<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nganh;
use Illuminate\Support\Facades\DB;

class NganhController extends Controller
{
    public function index(Request $request)
{
    $query = DB::table('nganh')
        ->join('khoa', 'nganh.ma_khoa', '=', 'khoa.ma_khoa')
        ->select(
            'nganh.ma_nganh',
            'nganh.ten_nganh',
            'nganh.thoi_gian_dao_tao',
            'nganh.bac_dao_tao',
            'nganh.ma_khoa',
            'khoa.ten_khoa'
        );

    // Lọc theo mã ngành, tên ngành nếu có
    if ($request->filled('ma_nganh')) {
        $query->where('nganh.ma_nganh', 'like', '%' . $request->ma_nganh . '%');
    }
    if ($request->filled('ten_nganh')) {
        $query->where('nganh.ten_nganh', 'like', '%' . $request->ten_nganh . '%');
    }

    return response()->json($query->get());
}

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'ten_nganh' => 'required|string|max:100',
                'ma_khoa' => 'required|integer',
                'thoi_gian_dao_tao' => 'nullable|numeric',
                'bac_dao_tao' => 'nullable|string|max:50',
            ]);

            $nganh = Nganh::create($validated);

            return response()->json([
                'message' => '✅ Thêm ngành thành công!',
                'data' => $nganh
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '❌ Đã xảy ra lỗi khi thêm ngành',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function show(string $id)
    {
        $nganh = Nganh::findOrFail($id);
        return response()->json($nganh);
    }

    public function update(Request $request, string $id)
    {
        try {
            $nganh = Nganh::findOrFail($id);

            $validated = $request->validate([
                'ten_nganh' => 'required|string|max:100',
                'ma_khoa' => 'required|integer',
                'thoi_gian_dao_tao' => 'nullable|numeric',
                'bac_dao_tao' => 'nullable|string|max:50',
            ]);

            $nganh->update($validated);

            return response()->json([
                'message' => '✅ Cập nhật ngành thành công!',
                'data' => $nganh
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '❌ Lỗi khi cập nhật ngành',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $nganh = Nganh::findOrFail($id);
        $nganh->delete();

        return response()->json([
            'message' => '🗑️ Đã xóa ngành thành công!'
        ]);
    }
}
