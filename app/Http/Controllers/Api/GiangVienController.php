<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;



use Illuminate\Http\Request;
use App\Models\GiangVien;

class GiangVienController extends Controller
{
        /**
     * Danh sách giảng viên (API).
     */
    public function index(Request $request)
    {
        $query = GiangVien::query();

        if ($request->has('ma_giang_vien')) {
            $query->where('ma_giang_vien', 'like', '%' . $request->ma_giang_vien . '%');
        }

        if ($request->has('ho_ten')) {
            $query->where('ho_ten', 'like', '%' . $request->ho_ten . '%');
        }

        return response()->json($query->get());
    }

    /**
     * Thêm mới giảng viên (API).
     */
   public function store(Request $request)
{
    try {
            Log::info('Store GiangVien chạy rồi!');
Log::info('Dữ liệu gửi lên:', $request->all());

        $validated = $request->validate([
            'ma_giang_vien' => 'required|string|max:10|unique:giang_vien',
            'user_id' => 'required|integer',
            'ma_khoa' => 'required|integer',
            'ho_ten' => 'required|string|max:50',
            'email' => 'required|email|unique:giang_vien',
            'so_dien_thoai' => 'nullable|string|max:15',
            'chuyen_nganh' => 'nullable|string|max:50',
        ]);

        $gv = GiangVien::create($validated);

        return response()->json([
            'message' => 'Thêm giảng viên thành công!',
            'data' => $gv
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Lỗi khi thêm giảng viên',
            'error' => $e->getMessage(),
        ], 500);
    }
}


    /**
     * Chi tiết giảng viên (API).
     */
    public function show($ma_giang_vien)
    {
        $gv = GiangVien::where('ma_giang_vien', $ma_giang_vien)->firstOrFail();
        return response()->json($gv);
    }

    /**
     * Cập nhật giảng viên (API).
     */
    public function update(Request $request, $ma_giang_vien)
    {
        $gv = GiangVien::where('ma_giang_vien', $ma_giang_vien)->firstOrFail();

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'ma_khoa' => 'required|integer',
            'ho_ten' => 'required|string|max:50',
            'email' => 'required|email|unique:giang_vien,email,' . $gv->ma_giang_vien . ',ma_giang_vien',
            'so_dien_thoai' => 'nullable|string|max:15',
            'chuyen_nganh' => 'nullable|string|max:50',
        ]);

        $gv->update($validated);
        return response()->json($gv);
    }

    /**
     * Xóa giảng viên (API).
     */
    public function destroy($ma_giang_vien)
    {
        $gv = GiangVien::where('ma_giang_vien', $ma_giang_vien)->firstOrFail();
        $gv->delete();

        return response()->json(['message' => 'Đã xóa giảng viên']);
    }

}
