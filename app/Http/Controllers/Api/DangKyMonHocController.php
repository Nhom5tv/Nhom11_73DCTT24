<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DangKyMonHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DangKyMonHocController extends Controller
{
    // Lấy tất cả bản ghi
public function index(Request $request)
{
    $query = DangKyMonHoc::with('lopHoc'); // << quan trọng

    if ($request->filled('ma_mon')) {
        $query->where('ma_mon', 'like', '%' . $request->ma_mon . '%');
    }

    if ($request->filled('ma_sinh_vien')) {
        $query->where('ma_sinh_vien', 'like', '%' . $request->ma_sinh_vien . '%');
    }

    $ds = $query->get()->map(function ($item) {
    // Kiểm tra nếu đã đủ dữ liệu để duyệt
    if (
        $item->trang_thai === 'Đang Chờ Duyệt' &&
        $item->ma_lop &&
        ($item->lich_hoc_du_kien || optional($item->lopHoc)->lich_hoc)
    ) {
        $item->trang_thai = 'Đã Duyệt';
        $item->save(); // cập nhật vào DB
    }

    return [
        'ma_dang_ky' => $item->ma_dang_ky,
        'ma_mon' => $item->ma_mon,
        'ma_sinh_vien' => $item->ma_sinh_vien,
        'ma_lop' => $item->ma_lop,
        'lich_hoc_du_kien' => $item->lich_hoc_du_kien ?? optional($item->lopHoc)->lich_hoc,
        'trang_thai' => $item->trang_thai
    ];
});


    return response()->json($ds);
}


    // Lấy một bản ghi theo ID
    public function show($id)
    {
        $dangKy = DangKyMonHoc::find($id);

        if (!$dangKy) {
            return response()->json(['message' => 'Không tìm thấy đăng ký'], 404);
        }

        return response()->json($dangKy);
    }

    // Thêm mới
    public function store(Request $request)
    {
        $request->validate([
            'ma_mon' => 'required|string|max:10',
            'ma_sinh_vien' => 'required|string|max:10',
            'ma_lop' => 'nullable|integer',
            'lich_hoc_du_kien' => 'nullable|string|max:255',
            'trang_thai' => 'nullable|string|max:20',
        ]);

        $dangKy = DangKyMonHoc::create($request->all());

        return response()->json($dangKy, 201);
    }

    // Cập nhật
    public function update(Request $request, $id)
    {
        $dangKy = DangKyMonHoc::find($id);

        if (!$dangKy) {
            return response()->json(['message' => 'Không tìm thấy đăng ký'], 404);
        }

        $request->validate([
            'ma_mon' => 'sometimes|string|max:10',
            'ma_sinh_vien' => 'sometimes|string|max:10',
            'ma_lop' => 'nullable|integer',
            'lich_hoc_du_kien' => 'nullable|string|max:255',
            'trang_thai' => 'nullable|string|max:20',
        ]);

        $dangKy->update($request->all());

        return response()->json($dangKy);
    }

    // Xoá
    public function destroy($id)
    {
        $dangKy = DangKyMonHoc::find($id);

        if (!$dangKy) {
            return response()->json(['message' => 'Không tìm thấy đăng ký'], 404);
        }

        $dangKy->delete();

        return response()->json(['message' => 'Đã xoá đăng ký thành công']);
    }
    public function huyTatCa()
    {
        $soDong = DangKyMonHoc::where('trang_thai', '!=', 'Đã huỷ')->update([
            'trang_thai' => 'Đã huỷ'
        ]);

        return response()->json([
            'message' => "Đã huỷ tất cả ($soDong dòng) đăng ký."
        ]);
    }
    public function getDanhSachMaMonDaDangKy()
{
    try {
        $ds = DB::table('dang_ky_mon_hoc AS dk')
            ->join('mon_hoc AS mh', 'dk.ma_mon', '=', 'mh.ma_mon')
            ->select('dk.ma_mon', 'mh.ten_mon')
            ->where('dk.trang_thai', '=', 'Đang Chờ Duyệt')
            ->distinct() // chỉ lấy mỗi mã môn 1 lần
            ->get();

        return response()->json($ds);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Lỗi server: ' . $e->getMessage()], 500);
    }
}

}
