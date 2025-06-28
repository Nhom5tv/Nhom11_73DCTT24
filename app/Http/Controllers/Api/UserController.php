<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Đổi mật khẩu cho người dùng đang đăng nhập
     * - Dùng cho cả trường hợp đổi mật khẩu lần đầu và đổi từ profile
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);
         /** @var \App\Models\User $user */
        $user = Auth::guard('api')->user();

        // Cập nhật mật khẩu mới
        // $user->password = Hash::make($request->password);
        $user->password = $request->password;
        // Nếu là lần đổi đầu tiên thì gỡ bắt buộc đổi
        if ($user->must_change_password) {
            $user->must_change_password = false;
        }

        $user->save();

        return response()->json([
            'message' => 'Đổi mật khẩu thành công'
        ]);
    }
}