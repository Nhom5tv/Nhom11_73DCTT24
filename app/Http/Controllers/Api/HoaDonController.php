<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HoaDon;
use App\Models\KhoanThuSinhVien;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class HoaDonController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = HoaDon::with('khoanThu:ma_khoan_thu,ten_khoan_thu')
            ->where('da_huy', false); // chỉ lấy hóa đơn chưa bị hủy

        if ($request->filled('ma_sinh_vien')) {
            $query->where('ma_sinh_vien', 'like', $request->ma_sinh_vien . '%');
        }

        if ($request->filled('ngay_thanh_toan')) {
            $query->whereDate('ngay_thanh_toan', $request->ngay_thanh_toan);
        }

        return response()->json($query->get());
    }

    public function store(Request $request): JsonResponse
    {
       

        $validated = $request->validate([
            'ma_sinh_vien' => 'required|string|max:10',
            'ma_khoan_thu' => 'required|integer',
            'ngay_thanh_toan' => 'required|date',
            'so_tien_da_nop' => 'required|numeric|min:0',
            'hinh_thuc_thanh_toan' => 'nullable|string|max:50',
            'noi_dung' => 'nullable|string|max:255'
        ]);

        DB::beginTransaction();
        try {
            $hoaDon = HoaDon::create($validated);
            $this->capNhatTrangThai($validated['ma_khoan_thu'], $validated['ma_sinh_vien']);

            DB::commit();
            return response()->json(['message' => 'Tạo hóa đơn thành công', 'data' => $hoaDon]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }

    public function cancel($id): JsonResponse
    {

        $hoaDon = HoaDon::findOrFail($id);

        if ($hoaDon->da_huy) {
            return response()->json(['message' => 'Hóa đơn đã bị hủy trước đó'], 400);
        }

        $hoaDon->update(['da_huy' => true]);

        $this->capNhatTrangThai($hoaDon->ma_khoan_thu, $hoaDon->ma_sinh_vien);

        return response()->json(['message' => 'Hủy hóa đơn thành công']);
    }

    private function capNhatTrangThai($maKhoanThu, $maSinhVien): void
    {
    

        $tongTienDaDong = HoaDon::where('ma_khoan_thu', $maKhoanThu)
            ->where('ma_sinh_vien', $maSinhVien)
            ->where('da_huy', false)
            ->sum('so_tien_da_nop');

    

        $kt_sv = KhoanThuSinhVien::where('ma_khoan_thu', $maKhoanThu)
            ->where('ma_sinh_vien', $maSinhVien)
            ->first();

        if (!$kt_sv) {
            Log::warning("Không tìm thấy KhoanThuSinhVien cho SV: $maSinhVien | Khoản: $maKhoanThu");
            return;
        }

        $soTienPhaiNop = $kt_sv->so_tien_phai_nop ?? 0;
    

        $trangThai = 'Chưa thanh toán';
        if ($tongTienDaDong >= $soTienPhaiNop && $soTienPhaiNop > 0) {
            $trangThai = 'Đã thanh toán';
        } elseif ($tongTienDaDong > 0 && $tongTienDaDong < $soTienPhaiNop) {
            $trangThai = 'Thanh toán 1 phần';
        }

        KhoanThuSinhVien::where('ma_khoan_thu', $maKhoanThu)
        ->where('ma_sinh_vien', $maSinhVien)
        ->update(['trang_thai_thanh_toan' => $trangThai]);

        
    }

    /// controller bên trang sinh viên

    public function getHoaDon(): JsonResponse
{
    $user = Auth::user();
    if (!$user || !$user->sinhVien) {
         return response()->json(['message' => 'Tài khoản chưa được liên kết với sinh viên'], 401);
    }

    $sinhVien = $user->sinhVien;
    $maSinhVien = $sinhVien->ma_sinh_vien;

    if (!$maSinhVien) {
        return response()->json(['error' => 'Thiếu mã sinh viên'], 400);
    }

    $dsHoaDon = HoaDon::with('khoanThu:ma_khoan_thu,ten_khoan_thu')
        ->where('ma_sinh_vien', $maSinhVien)
        ->where('da_huy', false)
        ->get();

    return response()->json($dsHoaDon);
}

public function getKhoanPhaiNop(): JsonResponse
    {
        $user = Auth::user();
        if (!$user || !$user->sinhVien) {
            return response()->json(['message' => 'Tài khoản chưa được liên kết với sinh viên'], 401);
        }

        $sinhVien = $user->sinhVien;
        $maSinhVien = $sinhVien->ma_sinh_vien;
        if (!$maSinhVien) {
            return response()->json(['error' => 'Thiếu mã sinh viên'], 400);
        }

        $dsPhaiNop = KhoanThuSinhVien::with('khoanThu:ma_khoan_thu,ten_khoan_thu,ngay_tao,han_nop')
            ->where('ma_sinh_vien', $maSinhVien)
            ->get();

        return response()->json($dsPhaiNop);
    }


}
