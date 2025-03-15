<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (Auth::user()->role !== $role) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Không có quyền truy cập'], 403);
            }
            
            if (Auth::user()->role === 'employee') {
                return redirect()->route('employee.profile');
            }
            
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập vào trang này');
        }

        return $next($request);
    }
}