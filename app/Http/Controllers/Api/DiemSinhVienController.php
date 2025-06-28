<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiemTheoLop;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DiemSinhVienController extends Controller
{
    public function getDiem(Request $request)
    {
        try {
            $ma_sinh_vien = $request->ma_sinh_vien;

            $rows = DB::table('diem_theo_lops')
                ->join('dang_ky_mon_hoc', function ($join) {
                    $join->on('diem_theo_lops.ma_lop', '=', 'dang_ky_mon_hoc.ma_lop')
                        ->on('diem_theo_lops.ma_sinh_vien', '=', 'dang_ky_mon_hoc.ma_sinh_vien');
                })
                ->join('mon_hoc', 'dang_ky_mon_hoc.ma_mon', '=', 'mon_hoc.ma_mon')
                ->where('diem_theo_lops.ma_sinh_vien', $ma_sinh_vien)
                ->select(
                    'mon_hoc.ma_mon',
                    'mon_hoc.ten_mon',
                    'mon_hoc.so_tin_chi',
                    'diem_theo_lops.lan_hoc',
                    'diem_theo_lops.diem_chuyen_can',
                    'diem_theo_lops.diem_giua_ky',
                    'diem_theo_lops.diem_cuoi_ky',
                    'diem_theo_lops.ma_lop'
                )
                ->get();

            $results = [];

            foreach ($rows as $index => $row) {
                $diem_he_10 = $row->diem_chuyen_can * 0.1 + $row->diem_giua_ky * 0.3 + $row->diem_cuoi_ky * 0.6;

                if ($diem_he_10 >= 8.5) {
                    $diem_he_4 = 4.0;
                    $diem_chu = 'A';
                } elseif ($diem_he_10 >= 8.0) {
                    $diem_he_4 = 3.5;
                    $diem_chu = 'B+';
                } elseif ($diem_he_10 >= 7.0) {
                    $diem_he_4 = 3.0;
                    $diem_chu = 'B';
                } elseif ($diem_he_10 >= 6.0) {
                    $diem_he_4 = 2.5;
                    $diem_chu = 'C+';
                } elseif ($diem_he_10 >= 5.5) {
                    $diem_he_4 = 2.0;
                    $diem_chu = 'C';
                } elseif ($diem_he_10 >= 5.0) {
                    $diem_he_4 = 1.5;
                    $diem_chu = 'D+';
                } elseif ($diem_he_10 >= 4.0) {
                    $diem_he_4 = 1.0;
                    $diem_chu = 'D';
                } else {
                    $diem_he_4 = 0.0;
                    $diem_chu = 'F';
                }

                $results[] = [
                    'stt' => $index + 1,
                    'ma_mon' => $row->ma_mon,
                    'ten_mon' => $row->ten_mon,
                    'so_tin_chi' => $row->so_tin_chi,
                    'lan_hoc' => $row->lan_hoc,
                    'diem_he_10' => round($diem_he_10, 2),
                    'diem_he_4' => $diem_he_4,
                    'diem_chu' => $diem_chu,
                    'danh_gia' => $diem_he_10 >= 4.0 ? 'Đạt' : 'Thi lại',
                    'ghi_chu' => '',
                    // 'chi_tiet' => url('/api/sinhvien/diem-chi-tiet/' . $ma_sinh_vien)
                    'chi_tiet' => url('/api/sinhvien/diem-chi-tiet?ma_sinh_vien=' . $ma_sinh_vien . '&ma_lop=' . $row->ma_lop),
                    'ma_lop' => $row->ma_lop
                ];
            }

            return response()->json($results);
        } catch (\Throwable $e) {
            Log::error('Lỗi lấy điểm bằng JOIN: ' . $e->getMessage());
            return response()->json(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()], 500);
        }
    }

    public function getDiemChiTiet(Request $request)
    {
        $ma_sinh_vien = $request->query('ma_sinh_vien');
        $ma_lop = $request->query('ma_lop');

        if (!$ma_sinh_vien) {
            return response()->json(['error' => 'Thiếu mã sinh viên'], 400);
        }

        try {
            $query = DiemTheoLop::where('ma_sinh_vien', $ma_sinh_vien);

            if ($ma_lop) {
                $query->where('ma_lop', $ma_lop);
            }

            $diem = $query->first();

            if (!$diem) {
                return response()->json([
                    'message' => 'Không tìm thấy điểm',
                    'ma_sinh_vien' => $ma_sinh_vien,
                    'ma_lop' => $ma_lop
                ], 404);
            }

            $diem_he_10 = $diem->diem_chuyen_can * 0.1 + $diem->diem_giua_ky * 0.3 + $diem->diem_cuoi_ky * 0.6;

            return response()->json([
                'ma_sinh_vien' => $diem->ma_sinh_vien,
                'ma_lop' => $diem->ma_lop,
                'lan_hoc' => $diem->lan_hoc,
                'diem_chuyen_can' => $diem->diem_chuyen_can,
                'diem_giua_ky' => $diem->diem_giua_ky,
                'diem_cuoi_ky' => $diem->diem_cuoi_ky,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function thongKeHocLuc()
    {
        $ketQua = DB::table('diem_theo_lops')
            ->selectRaw("
                CASE
                    WHEN diem_chuyen_can * 0.1 + diem_giua_ky * 0.3 + diem_cuoi_ky * 0.6 >= 9 THEN 'Xuất sắc'
                    WHEN diem_chuyen_can * 0.1 + diem_giua_ky * 0.3 + diem_cuoi_ky * 0.6 >= 8 THEN 'Giỏi'
                    WHEN diem_chuyen_can * 0.1 + diem_giua_ky * 0.3 + diem_cuoi_ky * 0.6 >= 7 THEN 'Khá'
                    WHEN diem_chuyen_can * 0.1 + diem_giua_ky * 0.3 + diem_cuoi_ky * 0.6 >= 5 THEN 'Trung bình'
                    ELSE 'Yếu'
                END as hoc_luc,
                COUNT(*) as so_luong
            ")
            ->groupBy('hoc_luc')
            ->get();

        return response()->json($ketQua);
    }
}
