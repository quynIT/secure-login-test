<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPasswordChangeRequired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra nếu người dùng cần thay đổi mật khẩu
        if (Auth::check() && Auth::user()->force_password_change) {
            // Kiểm tra role
            if (Auth::user()->role === 'root') {
                // Admin/root sẽ chuyển đến trang troll nếu không ở các trang liên quan
                if (!$request->routeIs('password.change') && 
                    !$request->routeIs('password.update') && 
                    !$request->routeIs('admin.question') &&
                    !$request->routeIs('troll.answer')) {
                    return redirect()->route('admin.question');
                }
            } else {
                // Nhân viên thường sẽ chuyển đến trang đổi mật khẩu như cũ
                if (!$request->routeIs('password.change') && 
                    !$request->routeIs('password.update')) {
                    
                    return redirect()->route('password.change');
                }
            }
        }
        return $next($request);
    }
}