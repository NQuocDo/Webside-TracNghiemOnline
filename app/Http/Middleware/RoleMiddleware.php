<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware // <-- Đổi tên class
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login')->withErrors(['access' => 'Bạn cần đăng nhập để truy cập trang này.']);
        }

        $user = Auth::user();

        // ✅ Kiểm tra trạng thái tài khoản trước
        if ($user->trang_thai_tai_khoan === 'khong_hoat_dong') {
            return redirect()->back()->with('error', 'Tài khoản của bạn đã bị khoá. Vui lòng liên hệ quản trị viên.');
        }

        // ✅ Kiểm tra vai trò
        if (!in_array($user->vai_tro, $roles)) {
            return redirect('/login')->withErrors(['access' => 'Bạn không có quyền truy cập trang này.']);
        }

        return $next($request);
    }
}