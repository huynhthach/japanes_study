<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (Auth::check()) {
            // Lấy vai trò của người dùng
            $userRole = Auth::user()->Role;
    
            // Kiểm tra và chuyển hướng
            if ($userRole == 1) {
                // Nếu là role 2 (admin), cho phép truy cập
                return $next($request);
            }
    
            // Chuyển hướng đến trang trước đó hoặc trang home nếu không có trang trước đó
            return redirect()->route('index');
        }
    
        // Nếu người dùng chưa đăng nhập, chuyển hướng về trang đăng nhập
        return redirect()->route('login');
    }
}
