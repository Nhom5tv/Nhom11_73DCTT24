<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (!$token = Auth::guard('api')->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $user = Auth::guard('api')->user();
    $userWithRelations = User::with(['giangVien', 'sinhVien'])->find($user->id);

    // Chuẩn bị dữ liệu user gọn nhẹ
    $userData = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role,
    ];

    if ($user->role === 'sinhvien') {
        $userData['ma_sinh_vien'] = optional($userWithRelations->sinhVien)->ma_sinh_vien;
    } elseif ($user->role === 'giaovien') {
        $userData['ma_giang_vien'] = optional($userWithRelations->giangVien)->ma_giang_vien;
    }

    return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => JWTAuth::factory()->getTTL() * 180,
        'must_change_password' => $user->must_change_password,
        'user' => $userData,
    ]);
}




    public function me()
    {
        $user = Auth::guard('api')->user();

        $ma_sinh_vien = null;
        $ma_giang_vien = null;

        if ($user->role === 'sinhvien') {
            $ma_sinh_vien = optional($user->sinhVien)->ma_sinh_vien;
        } elseif ($user->role === 'giaovien') {
            $ma_giang_vien = optional($user->giangVien)->ma_giang_vien;
        }

        return response()->json([
            'user' => $user,
            'ma_sinh_vien' => $ma_sinh_vien,
            'ma_giang_vien' => $ma_giang_vien,
        ]);
    }



}
