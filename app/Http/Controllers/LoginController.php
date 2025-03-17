<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Hiển thị form đăng nhập
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(Request $request)
    {
        try {
            $result = AuthService::getInstance()->login($request);
            
            if ($request->expectsJson()) {
                return response()->json($result);
            }
            
            return redirect($result['redirect']);
        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            
            throw $e;
        }
    }

    /**
     * Đăng xuất khỏi hệ thống
     */
    public function logout(Request $request)
    {
        AuthService::getInstance()->logout($request);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('login')
            ]);
        }
        
        return redirect()->route('login');
    }
    
    /**
     * Hiển thị form thay đổi mật khẩu
     */
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }
    
    /**
     * Xử lý thay đổi mật khẩu
     */
    public function changePassword(Request $request)
    {
        try {
            $result = AuthService::getInstance()->changePassword($request);
            
            if ($request->expectsJson()) {
                return response()->json($result);
            }
            
            $user = $result['user'];
            $route = $user->role === User::ROLE_ADMIN ? 'users.index' : 'employee.dashboard';
            return redirect()->route($route)->with('success', $result['message']);
        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            
            throw $e;
        }
    }
}