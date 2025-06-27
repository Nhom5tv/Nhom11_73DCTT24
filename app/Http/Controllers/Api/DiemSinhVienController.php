<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiemTheoLop;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DiemSinhVienController extends Controller
{
    // public function getDiem(Request $request)
    // {
    //     try {
    //     // ... toàn bộ xử lý ở đây
    //     Log::info('Request data:', $request->all());
    //     $ma_sinh_vien = $request->ma_sinh_vien;

    //     // Lấy danh sách điểm có kèm thông tin môn học qua quan hệ Eloquent
    //     $diemList = DiemTheoLop::with(['dangKyMonHoc.monHoc'])
    //         ->where('ma_sinh_vien', $ma_sinh_vien)
    //         ->get();

    //     $results = [];

    //     foreach ($diemList as $index => $diem) {
    //         $dangKy = $diem->dangKyMonHoc;
    //         $monHoc = $dangKy?->monHoc;

    //         if ($dangKy && $monHoc) {
    //             // Tính điểm hệ 10
    //             $diem_he_10 = $diem->diem_chuyen_can * 0.1 + $diem->diem_giua_ky * 0.3 + $diem->diem_cuoi_ky * 0.6;

    //             // Quy đổi điểm hệ 4 và điểm chữ
    //             if ($diem_he_10 >= 8.5) {
    //                 $diem_he_4 = 4.0;
    //                 $diem_chu = 'A';
    //             } elseif ($diem_he_10 >= 8.0) {
    //                 $diem_he_4 = 3.5;
    //                 $diem_chu = 'B+';
    //             } elseif ($diem_he_10 >= 7.0) {
    //                 $diem_he_4 = 3.0;
    //                 $diem_chu = 'B';
    //             } elseif ($diem_he_10 >= 6.0) {
    //                 $diem_he_4 = 2.5;
    //                 $diem_chu = 'C+';
    //             } elseif ($diem_he_10 >= 5.5) {
    //                 $diem_he_4 = 2.0;
    //                 $diem_chu = 'C';
    //             } elseif ($diem_he_10 >= 5.0) {
    //                 $diem_he_4 = 1.5;
    //                 $diem_chu = 'D+';
    //             } elseif ($diem_he_10 >= 4.0) {
    //                 $diem_he_4 = 1.0;
    //                 $diem_chu = 'D';
    //             } else {
    //                 $diem_he_4 = 0.0;
    //                 $diem_chu = 'F';
    //             }

    //             $danh_gia = $diem_he_10 >= 4.0 ? 'Đạt' : 'Thi lại';

    //             $results[] = [
    //                 'stt' => $index + 1,
    //                 'ma_mon' => $monHoc->ma_mon,
    //                 'ten_mon' => $monHoc->ten_mon,
    //                 'so_tin_chi' => $monHoc->so_tin_chi,
    //                 'lan_hoc' => $diem->lan_hoc,
    //                 'diem_he_10' => round($diem_he_10, 2),
    //                 'diem_he_4' => $diem_he_4,
    //                 'diem_chu' => $diem_chu,
    //                 'danh_gia' => $danh_gia,
    //                 'ghi_chu' => '',
    //                 'chi_tiet' => dd(url('/api/sinhvien/diem-chi-tiet/' . $diem->ma_sinh_vien))
    //             ];
    //         }
    //     }

    //     return response()->json($results);
    //     } catch (\Throwable $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
        
    // }
//     public function getDiem(Request $request, $ma_sinh_vien = null)
// {
//     try {
//         // Xác định mã sinh viên từ request hoặc route parameter
//         $ma_sinh_vien = $ma_sinh_vien ?? $request->ma_sinh_vien;
        
//         if (!$ma_sinh_vien) {
//             return response()->json(['error' => 'Thiếu mã sinh viên'], 400);
//         }

//         Log::info('Fetching scores for student:', ['ma_sinh_vien' => $ma_sinh_vien]);

//         // Sử dụng join thay vì with() để xử lý composite key
//         $diemList = DiemTheoLop::select('diem_theo_lops.*')
//             ->join('dang_ky_mon_hoc', function($join) {
//                 $join->on('dang_ky_mon_hoc.ma_sinh_vien', '=', 'diem_theo_lops.ma_sinh_vien')
//                     ->on('dang_ky_mon_hoc.ma_lop', '=', 'diem_theo_lops.ma_lop');
//             })
//             ->with(['dangKyMonHoc.monHoc'])
//             ->where('diem_theo_lops.ma_sinh_vien', $ma_sinh_vien)
//             ->get();

//         if ($diemList->isEmpty()) {
//             Log::warning('No scores found for student:', ['ma_sinh_vien' => $ma_sinh_vien]);
//             return response()->json(['message' => 'Không tìm thấy điểm cho sinh viên này'], 404);
//         }

//         $results = [];

//         foreach ($diemList as $index => $diem) {
//             $dangKy = $diem->dangKyMonHoc;
//             $monHoc = $dangKy?->monHoc;

//             if ($dangKy && $monHoc) {
//                 // Tính điểm hệ 10
//                 $diem_he_10 = $diem->diem_chuyen_can * 0.1 + $diem->diem_giua_ky * 0.3 + $diem->diem_cuoi_ky * 0.6;

//                 // Quy đổi điểm hệ 4 và điểm chữ
//                 if ($diem_he_10 >= 8.5) {
//                     $diem_he_4 = 4.0;
//                     $diem_chu = 'A';
//                 } elseif ($diem_he_10 >= 8.0) {
//                     $diem_he_4 = 3.5;
//                     $diem_chu = 'B+';
//                 } elseif ($diem_he_10 >= 7.0) {
//                     $diem_he_4 = 3.0;
//                     $diem_chu = 'B';
//                 } elseif ($diem_he_10 >= 6.0) {
//                     $diem_he_4 = 2.5;
//                     $diem_chu = 'C+';
//                 } elseif ($diem_he_10 >= 5.5) {
//                     $diem_he_4 = 2.0;
//                     $diem_chu = 'C';
//                 } elseif ($diem_he_10 >= 5.0) {
//                     $diem_he_4 = 1.5;
//                     $diem_chu = 'D+';
//                 } elseif ($diem_he_10 >= 4.0) {
//                     $diem_he_4 = 1.0;
//                     $diem_chu = 'D';
//                 } else {
//                     $diem_he_4 = 0.0;
//                     $diem_chu = 'F';
//                 }

//                 $danh_gia = $diem_he_10 >= 4.0 ? 'Đạt' : 'Thi lại';

//                 $results[] = [
//                     'stt' => $index + 1,
//                     'ma_mon' => $monHoc->ma_mon,
//                     'ten_mon' => $monHoc->ten_mon,
//                     'so_tin_chi' => $monHoc->so_tin_chi,
//                     'lan_hoc' => $diem->lan_hoc,
//                     'diem_he_10' => round($diem_he_10, 2),
//                     'diem_he_4' => $diem_he_4,
//                     'diem_chu' => $diem_chu,
//                     'danh_gia' => $danh_gia,
//                     'ghi_chu' => '',
//                     'chi_tiet' => url('/api/sinhvien/diem-chi-tiet/' . $diem->ma_sinh_vien)
//                 ];
//             }
//         }

//         return response()->json($results);

//     } catch (\Throwable $e) {
//         Log::error('Error fetching scores:', [
//             'error' => $e->getMessage(),
//             'trace' => $e->getTraceAsString()
//         ]);
//         return response()->json(['error' => 'Lỗi hệ thống khi lấy điểm'], 500);
//     }
// }
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
                'diem_theo_lops.diem_cuoi_ky'
            )
            ->get();

        $results = [];

        foreach ($rows as $index => $row) {
            $diem_he_10 = $row->diem_chuyen_can * 0.1 + $row->diem_giua_ky * 0.3 + $row->diem_cuoi_ky * 0.6;

            if ($diem_he_10 >= 8.5) {
                $diem_he_4 = 4.0; $diem_chu = 'A';
            } elseif ($diem_he_10 >= 8.0) {
                $diem_he_4 = 3.5; $diem_chu = 'B+';
            } elseif ($diem_he_10 >= 7.0) {
                $diem_he_4 = 3.0; $diem_chu = 'B';
            } elseif ($diem_he_10 >= 6.0) {
                $diem_he_4 = 2.5; $diem_chu = 'C+';
            } elseif ($diem_he_10 >= 5.5) {
                $diem_he_4 = 2.0; $diem_chu = 'C';
            } elseif ($diem_he_10 >= 5.0) {
                $diem_he_4 = 1.5; $diem_chu = 'D+';
            } elseif ($diem_he_10 >= 4.0) {
                $diem_he_4 = 1.0; $diem_chu = 'D';
            } else {
                $diem_he_4 = 0.0; $diem_chu = 'F';
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
                'chi_tiet' => url('/api/sinhvien/diem-chi-tiet/' . $ma_sinh_vien)
            ];
        }

        return response()->json($results);
    } catch (\Throwable $e) {
        Log::error('Lỗi lấy điểm bằng JOIN: ' . $e->getMessage());
        return response()->json(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()], 500);
    }
}
    public function getDiemChiTiet($ma_sinh_vien)
    {
        try {
            // Lấy các bản ghi điểm của sinh viên
            $diem = DiemTheoLop::where('ma_sinh_vien', $ma_sinh_vien)->first();  // Chỉ lấy 1 bản ghi đầu tiên (có thể thay đổi)

            if (!$diem) {
                return response()->json(['message' => 'Không tìm thấy điểm của sinh viên'], 404);
            }

            // Trả về thông tin chi tiết điểm
            return response()->json([
                'ma_sinh_vien' => $diem->ma_sinh_vien,
                'lan_hoc' => $diem->lan_hoc,
                'diem_chuyen_can' => $diem->diem_chuyen_can,
                'diem_giua_ky' => $diem->diem_giua_ky,
                'diem_cuoi_ky' => $diem->diem_cuoi_ky
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
