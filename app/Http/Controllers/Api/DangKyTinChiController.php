<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DangKyTinChiController extends Controller
{
    /**
     * Lấy danh sách môn học có thể đăng ký
     */
    public function monHocCoTheDangKy(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user || !$user->sinhVien) {
                return response()->json(['message' => 'Tài khoản chưa được liên kết với sinh viên'], 401);
            }

            $sinhVien = $user->sinhVien;
            $ma_sinh_vien = $sinhVien->ma_sinh_vien;

            $ten_mon_hoc = $request->input('ten_mon_hoc');
            $lich_hoc_du_kien = $request->input('lich_hoc_du_kien');

            $query = DB::table('mon_hoc AS mh')
                ->leftJoin('lich_hoc AS lh', 'mh.ma_mon', '=', 'lh.ma_mon_hoc')
                ->leftJoin('dang_ky_mon_hoc AS dk', function ($join) use ($ma_sinh_vien) {
                    $join->on('mh.ma_mon', '=', 'dk.ma_mon')
                        ->where('dk.ma_sinh_vien', '=', $ma_sinh_vien);
                })
                ->leftJoin('dang_ky_mon_hoc AS dk2', 'mh.ma_mon', '=', 'dk2.ma_mon')
                ->select(
                    'lh.id_lich_hoc',
                    'mh.ma_mon AS ma_mon_hoc',
                    'mh.ten_mon AS ten_mon_hoc',
                    'mh.so_tin_chi',
                    'dk.ma_dang_ky',
                    'dk.trang_thai AS trang_thai_dang_ky',
                    'lh.so_luong_toi_da',
                    DB::raw('(IFNULL(lh.so_luong_toi_da, 0) - COUNT(dk2.ma_sinh_vien)) AS con_lai'),
                    'lh.lich_hoc AS lich_hoc_du_kien'
                )
                ->where('lh.trang_thai', '=', 'Đang Mở')
                ->groupBy(
                    'mh.ma_mon',
                    'mh.ten_mon',
                    'mh.so_tin_chi',
                    'dk.ma_dang_ky',
                    'dk.trang_thai',
                    'lh.so_luong_toi_da',
                    'lh.lich_hoc',
                    'lh.id_lich_hoc'
                );

            if ($ten_mon_hoc) {
                $query->where('mh.ten_mon', 'like', '%' . $ten_mon_hoc . '%');
            }

            if ($lich_hoc_du_kien) {
                $query->where('lh.lich_hoc', 'like', '%' . $lich_hoc_du_kien . '%');
            }
            // dd($query->toSql(), $query->getBindings());
            return response()->json($query->get());
        } catch (\Exception $e) {
            Log::error('Lỗi lấy danh sách môn học: ' . $e->getMessage());
        return response()->json(['message' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Đăng ký môn học
     */
    public function dangKyMonHoc(Request $request)
    {
        $user = Auth::user();
        $sinhVien = $user->sinhVien;

        $ma_mon = $request->input('ma_mon_hoc');
        $lich_hoc = $request->input('lich_hoc_du_kien');

        if (!$ma_mon || !$lich_hoc) {
            return response()->json(['message' => 'Thiếu thông tin đăng ký'], 400);
        }

        if (!$sinhVien) {
            return response()->json(['message' => 'Không tìm thấy sinh viên'], 404);
        }

        try {
            DB::table('dang_ky_mon_hoc')->insert([
                'ma_sinh_vien' => $sinhVien->ma_sinh_vien,
                'ma_mon' => $ma_mon,
                'trang_thai' => 'Đang Chờ Duyệt',
            ]);
            return response()->json(['message' => 'Đăng ký thành công']);
        } catch (\Exception $e) {
            Log::error('Lỗi đăng ký môn học: ' . $e->getMessage());
            return response()->json(['message' => 'Lỗi server'], 500);
        }
    }

    /**
     * Lấy danh sách môn học đã đăng ký
     */
    public function dsDaDangKy()
{
    $user = Auth::user();
    $sinhVien = $user->sinhVien;

    if (!$sinhVien) {
        return response()->json(['message' => 'Không tìm thấy sinh viên'], 404);
    }

    $ds = DB::table('dang_ky_mon_hoc AS dk')
        ->join('mon_hoc AS mh', 'dk.ma_mon', '=', 'mh.ma_mon')
        ->leftJoin('lich_hoc AS lh', 'mh.ma_mon', '=', 'lh.ma_mon_hoc')
        ->select(
            'dk.ma_dang_ky',
            'mh.ma_mon AS ma_mon_hoc',
            'mh.ten_mon AS ten_mon_hoc',
            'mh.so_tin_chi',
            'lh.so_luong_toi_da',
            DB::raw('(IFNULL(lh.so_luong_toi_da, 0) - (SELECT COUNT(*) FROM dang_ky_mon_hoc WHERE ma_mon = mh.ma_mon)) AS con_lai'),
            'lh.lich_hoc AS lich_hoc_du_kien',
            'dk.trang_thai' // ✅ chính xác là lấy từ bảng dk
        )
        ->where('dk.ma_sinh_vien', $sinhVien->ma_sinh_vien)
        ->get();

    return response()->json($ds);
}

    /**
     * Hủy đăng ký môn học
     */
    public function huyDangKyMonHoc($id)
    {
        $user = Auth::user();
        $sinhVien = $user->sinhVien;

        if (!$sinhVien) {
            return response()->json(['message' => 'Không tìm thấy sinh viên'], 404);
        }

        $dangKy = DB::table('dang_ky_mon_hoc')
            ->where('ma_dang_ky', $id)
            ->where('ma_sinh_vien', $sinhVien->ma_sinh_vien)
            ->first();

        if (!$dangKy) {
            return response()->json(['message' => 'Không tìm thấy đăng ký hoặc không thuộc về bạn'], 404);
        }

        try {
            DB::table('dang_ky_mon_hoc')
                ->where('ma_dang_ky', $id)
                ->delete();

            return response()->json(['message' => 'Hủy đăng ký thành công']);
        } catch (\Exception $e) {
            Log::error('Lỗi khi hủy đăng ký: ' . $e->getMessage());
            return response()->json(['message' => 'Lỗi server khi hủy đăng ký'], 500);
        }
    }
}
