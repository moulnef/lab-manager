<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Kiểm tra người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập với tài khoản Quản trị viên để truy cập trang này.');
        }

        // 2. Kiểm tra tài khoản có quyền Admin không
        if (Auth::user()->role !== 'admin') {
            // Nếu không phải admin, chặn truy cập (HTTP 403 Forbidden hoặc redirect về trang user)
            abort(403, 'Từ chối truy cập: Chỉ tài khoản Quản trị viên (Admin) mới có quyền truy cập trang này.');
        }

        return $next($request);
    }
}
