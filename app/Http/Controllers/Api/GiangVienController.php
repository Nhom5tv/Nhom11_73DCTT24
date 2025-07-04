<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;



use Illuminate\Http\Request;
use App\Models\GiangVien;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

    $giangVienList = $query
        ->leftJoin('khoa', 'giang_vien.ma_khoa', '=', 'khoa.ma_khoa')
        ->select('giang_vien.*', 'khoa.ten_khoa')
        ->get();

    return response()->json($giangVienList);
}

    /**
     * Thêm mới giảng viên (API).
     */

public function store(Request $request)
{
    DB::beginTransaction();

    try {

        $validated = $request->validate([
            'ma_giang_vien' => 'required|string|max:10|unique:giang_vien',
            'ma_khoa' => 'required|integer',
            'ho_ten' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email', // Kiểm tra trùng ở bảng users
            'so_dien_thoai' => 'nullable|string|max:15',
            'chuyen_nganh' => 'nullable|string|max:50',
        ]);

        // 1. Tạo tài khoản user
        $user = \App\Models\User::create([
            'name' => $validated['ho_ten'],
            'email' => $validated['email'],
            'password' => Hash::make('12345678'), 
            'role' => 'giaovien',
            'must_change_password' => true
        ]);

        // 2. Tạo giảng viên, gán user_id
        $giangVien = GiangVien::create([
            'ma_giang_vien' => $validated['ma_giang_vien'],
            'user_id' => $user->id,
            'ma_khoa' => $validated['ma_khoa'],
            'ho_ten' => $validated['ho_ten'],
            'email' => $validated['email'],
            'so_dien_thoai' => $validated['so_dien_thoai'] ?? null,
            'chuyen_nganh' => $validated['chuyen_nganh'] ?? null,
        ]);

        DB::commit();

        return response()->json([
            'message' => 'Tạo giảng viên và tài khoản thành công!',
            'giang_vien' => $giangVien,
            'user' => $user
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Lỗi khi tạo giảng viên:', ['error' => $e->getMessage()]);
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
            
            'ma_khoa' => 'required|integer',
            'ho_ten' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email,' . $gv->user_id,
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
    public function getThongTinGiangVien(Request $request)
{
    $user = Auth::user();

    $giangVien = \App\Models\GiangVien::where('user_id', $user->id)
        ->join('khoa', 'giang_vien.ma_khoa', '=', 'khoa.ma_khoa')
        ->select('giang_vien.*', 'khoa.ten_khoa')
        ->first();

    if (!$giangVien) {
        return response()->json(['message' => 'Không tìm thấy thông tin giảng viên'], 404);
    }

    return response()->json($giangVien);
}
public function updateThongTinGiangVien(Request $request)
{
    $user = Auth::user();

    // Tìm giảng viên tương ứng với user hiện tại
    $giangVien = \App\Models\GiangVien::where('user_id', $user->id)->first();

    if (!$giangVien) {
        return response()->json(['message' => 'Không tìm thấy giảng viên'], 404);
    }

    // Validate dữ liệu đầu vào
    $validated = $request->validate([
        'ho_ten' => 'required|string|max:100',
        'email' => 'required|email|max:100',
        'so_dien_thoai' => 'nullable|string|max:20',
        'chuyen_nganh' => 'nullable|string|max:100',
    ]);

    // Cập nhật thông tin
    $giangVien->update($validated);

    return response()->json([
        'message' => 'Cập nhật thông tin giảng viên thành công',
        'data' => $giangVien
    ]);

}
public function import(Request $request)
{
    $data = $request->all();

    $success = 0;
    $fail = 0;
    $errors = [];

    foreach ($data as $index => $row) {
        Log::info("📄 Import GV - Dòng $index", $row);

        try {
            // Kiểm tra trùng mã giảng viên
            if (\App\Models\GiangVien::where('ma_giang_vien', $row['ma_giang_vien'])->exists()) {
                $fail++;
                $errors[] = "Dòng " . ($index + 2) . ": Mã giảng viên '{$row['ma_giang_vien']}' đã tồn tại.";
                continue;
            }

            // Kiểm tra trùng email
            if (\App\Models\User::where('email', $row['email'])->exists()) {
                $fail++;
                $errors[] = "Dòng " . ($index + 2) . ": Email '{$row['email']}' đã tồn tại.";
                continue;
            }

            // Kiểm tra mã khoa tồn tại
            if (!DB::table('khoa')->where('ma_khoa', $row['ma_khoa'])->exists()) {
                $fail++;
                $errors[] = "Dòng " . ($index + 2) . ": Mã khoa '{$row['ma_khoa']}' không tồn tại.";
                continue;
            }

            // Tạo user (nếu giảng viên có quyền login sau này)
            $user = \App\Models\User::create([
                'name' => $row['ho_ten'],
                'email' => $row['email'],
                'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
                'role' => 'giaovien',
                'must_change_password' => true
            ]);

            // Tạo giảng viên
            \App\Models\GiangVien::create([
                'ma_giang_vien' => $row['ma_giang_vien'],
                'ma_khoa' => $row['ma_khoa'],
                'ho_ten' => $row['ho_ten'],
                'email' => $row['email'],
                'so_dien_thoai' => $row['so_dien_thoai'],
                'chuyen_nganh' => $row['chuyen_nganh'],
                'user_id' => $user->id
            ]);

            $success++;

        } catch (\Exception $e) {
            $fail++;
            $errors[] = "Dòng " . ($index + 2) . ": " . $e->getMessage();
        }
    }

    return response()->json([
        'message' => "✅ Import xong: $success thành công, $fail lỗi.",
        'errors' => $errors
    ]);
}



}
