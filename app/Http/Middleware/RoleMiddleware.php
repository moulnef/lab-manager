<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware phân quyền theo vai trò.
 * Cách dùng trong route: ->middleware(['auth', 'role:admin'])
 * Có thể truyền nhiều vai trò: 'role:admin,user'
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Chưa đăng nhập → chuyển về trang đăng nhập
        if (! $user) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        // Sai vai trò → chặn với mã 403 (Forbidden)
        if (! in_array($user->role, $roles, true)) {
            abort(403, 'Bạn không có quyền truy cập khu vực này.');
        }

        return $next($request);
    }
}
