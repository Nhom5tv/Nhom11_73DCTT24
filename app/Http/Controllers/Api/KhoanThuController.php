<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KhoanThu;
use App\Models\KhoanThuSinhVien;
use App\Models\MienGiamSinhVien;
use App\Models\SinhVien;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class KhoanThuController extends Controller
{
    public function index(Request $request)
{
    $query = KhoanThu::query();

    if ($request->filled('ten_khoan_thu')) {
        $query->where('ten_khoan_thu', 'like', '%' . $request->ten_khoan_thu . '%');
    }

    if ($request->filled('from_date')) {
        $query->whereDate('ngay_tao', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('han_nop', '<=', $request->to_date);
    }

    return response()->json($query->orderByDesc('ma_khoan_thu')->get());
}


   
    public function store(Request $request)
    {
        try {
            // Bước 1: Validate dữ liệu
            $validated = $request->validate([
                'ten_khoan_thu' => 'required|string|max:255|unique:khoan_thu,ten_khoan_thu',
                'loai_khoan_thu' => 'required|in:Học phí,BHYT,Khác',
                'so_tien' => 'required|numeric|min:0',
                'han_nop' => 'required|date'
            ]);

            $ngayTao = now()->toDateString();

            if ($validated['han_nop'] < $ngayTao) {
                return response()->json(['error' => 'Hạn nộp phải lớn hơn hoặc bằng ngày tạo!'], 422);
            }

            // Bước 2: Tạo khoản thu chung
            $khoanThu = KhoanThu::create([
                'ten_khoan_thu' => $validated['ten_khoan_thu'],
                'loai_khoan_thu' => $validated['loai_khoan_thu'],
                'so_tien' => $validated['so_tien'],
                'ngay_tao' => $ngayTao,
                'han_nop' => $validated['han_nop']
            ]);

            // Bước 3: Lấy danh sách sinh viên với khoa và tiền tín chỉ tương ứng
            $dsSinhVien = SinhVien::select('ma_sinh_vien', 'ho_ten', 'ma_khoa')
                ->with(['khoa:ma_khoa,tien_moi_tin_chi'])
                ->get();

            if ($dsSinhVien->isEmpty()) {
                return response()->json(['error' => 'Không có sinh viên nào để gán khoản thu!'], 400);
            }

            foreach ($dsSinhVien as $sv) {
                // Bước 4: Tính số tiền gốc
                if ($validated['loai_khoan_thu'] === 'Học phí') {
                    $tongTinChi = $sv->tongTinChiDangKy(); // phải định nghĩa trong model SinhVien
                    $donGia = $sv->khoa->tien_moi_tin_chi ?? 0;
                    $soTienGoc = $tongTinChi * $donGia;
                     Log::info("Học phí - Sinh viên: {$sv->ma_sinh_vien}, Tên: {$sv->ho_ten}, Tổng TC: $tongTinChi, Đơn giá/TC: $donGia");

                } else {
                    $soTienGoc = $validated['so_tien'];
                }

                // Bước 5: Tìm mức miễn giảm theo đúng loại khoản thu
                $mienGiam = MienGiamSinhVien::where('ma_sinh_vien', $sv->ma_sinh_vien)
                    ->where('loai_mien_giam', $validated['loai_khoan_thu'])
                    ->first();

                $mucGiam = $mienGiam->muc_tien ?? 0;

                // Giảm theo phần trăm
                $soTienMienGiam = ($soTienGoc * $mucGiam) / 100;
                $soTienPhaiNop = max(0, $soTienGoc - $soTienMienGiam);

                // $trangThai = $soTienPhaiNop == 0 ? 'Đã thanh toán' : 'Chưa thanh toán';
                $trangThai = round($soTienPhaiNop, 2) == 0 ? 'Đã thanh toán' : 'Chưa thanh toán';
                Log::info("Miễn giảm - SV: {$sv->ma_sinh_vien}, Mức giảm: $mucGiam%, Số tiền gốc: $soTienGoc, Giảm: $soTienMienGiam, Cần nộp: $soTienPhaiNop");

                // Bước 6: Gán khoản thu sinh viên
                KhoanThuSinhVien::create([
                    'ma_khoan_thu' => $khoanThu->ma_khoan_thu,
                    'ma_sinh_vien' => $sv->ma_sinh_vien,
                    'so_tien_ban_dau' => $soTienGoc,
                    'so_tien_mien_giam' => $soTienMienGiam,
                    'so_tien_phai_nop' => $soTienPhaiNop,
                    'trang_thai_thanh_toan' => $trangThai
                ]);
            }

            return response()->json([
                'message' => 'Tạo khoản thu, gán sinh viên và cập nhật miễn giảm thành công!',
                'data' => $khoanThu
            ]);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
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
                'han_nop' => 'required|date'
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
    $khoanThu = KhoanThu::findOrFail($id);

    // Kiểm tra xem có hóa đơn nào liên quan không
    $daCoSinhVienDong = $khoanThu->hoaDon()->exists(); // dùng quan hệ if có

    if ($daCoSinhVienDong) {
        return response()->json(['error' => 'Không thể xóa vì đã có sinh viên nộp tiền!'], 400);
    }

    // Nếu chưa ai nộp thì xóa toàn bộ liên quan
    KhoanThuSinhVien::where('ma_khoan_thu', $id)->delete();
    $khoanThu->delete();

    return response()->json(['message' => 'Xóa khoản thu thành công']);
}

}
