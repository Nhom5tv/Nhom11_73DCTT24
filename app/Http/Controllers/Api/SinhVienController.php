<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use App\Models\SinhVien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SinhVienController extends Controller
{
    /**
     * Hiển thị danh sách sinh viên.
     */
   public function index(Request $request)
{
    $query = SinhVien::query()
        ->with(['khoa', 'nganh']); // relationship phải khai báo trong model

    if ($request->filled('ma_sinh_vien')) {
        $query->where('ma_sinh_vien', 'like', '%' . $request->ma_sinh_vien . '%');
    }

    if ($request->filled('ho_ten')) {
        $query->where('ho_ten', 'like', '%' . $request->ho_ten . '%');
    }

    $result = $query->get()->map(function ($sv) {
        return [
            'ma_sinh_vien' => $sv->ma_sinh_vien,
            'user_id' => $sv->user_id,
            'ma_khoa' => $sv->ma_khoa,
            'ten_khoa' => $sv->khoa->ten_khoa ?? '',
            'ma_nganh' => $sv->ma_nganh,
            'ten_nganh' => $sv->nganh->ten_nganh ?? '',
            'ho_ten' => $sv->ho_ten,
            'ngay_sinh' => $sv->ngay_sinh,
            'gioi_tinh' => $sv->gioi_tinh,
            'que_quan' => $sv->que_quan,
            'email' => $sv->email,
            'so_dien_thoai' => $sv->so_dien_thoai,
            'khoa_hoc' => $sv->khoa_hoc,
        ];
    });

    return response()->json($result);
}

    /**
     * Thêm mới một sinh viên.
     */
  public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'ma_sinh_vien' => 'required|string|max:10|unique:sinh_vien',
            'ma_khoa' => 'required|integer',
            'ma_nganh' => 'required|integer',
            'ho_ten' => 'required|string|max:100',
            'ngay_sinh' => 'required|date',
            'gioi_tinh' => 'required|in:Nam,Nữ',
            'que_quan' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'so_dien_thoai' => 'required|string|max:15',
            'khoa_hoc' => 'required|integer',
        ]);

        // 1. Tạo tài khoản user
        $user = \App\Models\User::create([
            'name' => $validated['ho_ten'],
            'email' => $validated['email'],
            'password' => Hash::make('12345678'),
            'role' => 'sinhvien',
            'must_change_password' => true
        ]);

        // 2. Tạo sinh viên
        $sinhvien = SinhVien::create([
            'ma_sinh_vien' => $validated['ma_sinh_vien'],
            'user_id' => $user->id,
            'ma_khoa' => $validated['ma_khoa'],
            'ma_nganh' => $validated['ma_nganh'],
            'ho_ten' => $validated['ho_ten'],
            'ngay_sinh' => $validated['ngay_sinh'],
            'gioi_tinh' => $validated['gioi_tinh'],
            'que_quan' => $validated['que_quan'],
            'email' => $validated['email'],
            'so_dien_thoai' => $validated['so_dien_thoai'],
            'khoa_hoc' => $validated['khoa_hoc'],
        ]);

        DB::commit(); // Thành công: xác nhận ghi vào DB

        return response()->json([
            'message' => 'Tạo sinh viên và tài khoản thành công',
            'sinhvien' => $sinhvien,
            'user' => $user
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack(); // Có lỗi: rollback tất cả

        return response()->json([
            'message' => 'Đã xảy ra lỗi, hệ thống đã rollback',
            'error' => $e->getMessage(),
        ], 500);
    }
}



    /**
     * Lấy thông tin chi tiết 1 sinh viên.
     */
 public function getThongTinCaNhan(Request $request)
{
    try {
        $user = $request->user();

        // Lấy sinh viên kèm theo ngành và khoa (dùng with)
        $sinhvien = SinhVien::with(['nganh', 'khoa'])->where('user_id', $user->id)->first();

        if (!$sinhvien) {
            return response()->json([
                'message' => 'Không tìm thấy thông tin sinh viên'
            ], 404);
        }

        return response()->json([
            'ma_sinh_vien'   => $sinhvien->ma_sinh_vien,
            'ho_ten'         => $sinhvien->ho_ten,
            'ngay_sinh'      => $sinhvien->ngay_sinh,
            'gioi_tinh'      => $sinhvien->gioi_tinh,
            'que_quan'       => $sinhvien->que_quan,
            'email'          => $sinhvien->email,
            'so_dien_thoai'  => $sinhvien->so_dien_thoai,
            'khoa_hoc'       => $sinhvien->khoa_hoc,
            'ma_nganh'       => $sinhvien->ma_nganh,
            'ma_khoa'        => $sinhvien->ma_khoa,
            'ten_nganh'      => $sinhvien->nganh->ten_nganh ?? '(chưa có)', // 👈 thêm dòng này
            'ten_khoa'       => $sinhvien->khoa->ten_khoa ?? '(chưa có)'   // 👈 nếu cần
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Đã xảy ra lỗi khi lấy thông tin sinh viên.',
            'error'   => $e->getMessage(),
            'line'    => $e->getLine(),
            'file'    => $e->getFile(),
        ], 500);
    }
}


    /**
     * Cập nhật thông tin sinh viên.
     */
    public function update(Request $request, string $id)
    {
        $sinhvien = SinhVien::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'ma_khoa' => 'required|integer',
            'ma_nganh' => 'required|integer',
            'ho_ten' => 'required|string|max:100',
            'ngay_sinh' => 'required|date',
            'gioi_tinh' => 'required|in:Nam,Nữ',
            'que_quan' => 'required|string|max:100',
            'email' => 'required|email|unique:sinh_vien,email,' . $id . ',ma_sinh_vien',
            'so_dien_thoai' => 'required|string|max:15',
            'khoa_hoc' => 'required|integer',
        ]);

        $sinhvien->update($validated);
        return response()->json($sinhvien);
    }
    // Sinh viên tự cập nhật thông tin cá nhân (dựa vào user_id)
public function capNhatThongTinCaNhan(Request $request)
{
    $userId = auth()->id(); // hoặc $request->user()->id
    $sinhvien = SinhVien::where('user_id', $userId)->firstOrFail();

    $validated = $request->validate([
        'ma_khoa' => 'required|integer',
        'ma_nganh' => 'required|integer',
        'ho_ten' => 'required|string|max:100',
        'ngay_sinh' => 'required|date',
        'gioi_tinh' => 'required|in:Nam,Nữ',
        'que_quan' => 'required|string|max:100',
        'email' => 'required|email|unique:sinh_vien,email,' . $sinhvien->ma_sinh_vien . ',ma_sinh_vien',
        'so_dien_thoai' => 'required|string|max:15',
        'khoa_hoc' => 'required|integer',
    ]);

    $sinhvien->update($validated);

    return response()->json([
        'message' => 'Cập nhật thành công!',
        'data' => $sinhvien
    ]);
}


    /**
     * Xóa một sinh viên.
     */
    public function destroy(string $id)
    {
        $sinhvien = SinhVien::findOrFail($id);
        $sinhvien->delete();
        return response()->json(['message' => 'Đã xóa sinh viên thành công']);
    }
}
