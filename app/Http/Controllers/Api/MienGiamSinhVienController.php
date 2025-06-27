<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MienGiamSinhVien;
use Illuminate\Support\Facades\DB;
class MienGiamSinhVienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         return response()->json(MienGiamSinhVien::all(),200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ma_sinh_vien' => 'required|string|max:10',
            'muc_tien' => 'required|numeric',
            'loai_mien_giam' => 'nullable|string|max:50',
            'ghi_chu' => 'nullable|string|max:255',
        ]);
        $mienGiam = MienGiamSinhVien::create($validated);
        return response()->json($mienGiam, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($ma_mien_giam)
    {
        $mienGiam = MienGiamSinhVien::find($ma_mien_giam);
        if (!$mienGiam) return response()->json(['message' => 'không tìm thấy mã miễn giảm'], 404);
        return response()->json($mienGiam, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $ma_mien_giam)
    {
        $mienGiam = MienGiamSinhVien::find($ma_mien_giam);
        if(!$mienGiam) return response()->json(['message' => 'Không tìm thấy'], 404);
        
        $validated = $request->validate([
            'ma_sinh_vien' => 'required|string|max:10',
            'muc_tien' => 'required|numeric',
            'loai_mien_giam' => 'nullable|string|max:50',
            'ghi_chu' => 'nullable|string|max:255',
        ]);

        $mienGiam->update($validated);
        return response()->json($mienGiam, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ma_mien_giam)
    {
        $mienGiam = MienGiamSinhVien::find($ma_mien_giam);
        if(!$mienGiam) return response()->json(['message' => 'không tìm thấy'],status: 404);
        $mienGiam ->delete();
        return response()->json(['message'=>'Đã xóa thành công'],status:200);
    }

        public function thongKeMienGiam()
    {
        $ketQua = DB::table('mien_giam_sinh_vien')
            ->select('loai_mien_giam', DB::raw('COUNT(*) as so_luong'))
            ->groupBy('loai_mien_giam')
            ->get();

        return response()->json($ketQua);
    }
}
