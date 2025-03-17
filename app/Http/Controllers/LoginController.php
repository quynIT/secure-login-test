<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'remember' => 'nullable|boolean'
        ]);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $request->remember)) {
            $request->session()->regenerate();
            
            if (Auth::user()->force_password_change) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('password.change')
                ]);
            }
            
            if (Auth::user()->role === User::ROLE_ADMIN) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('users.index')
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'redirect' => route('employee.profile')
                ]);
            }
        }

        throw ValidationException::withMessages([
            'email' => ['Email hoặc mật khẩu không đúng.']
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
    
   
    //Hiển thị form thay đổi mật khẩu
     
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }
    

    // Xử lý thay đổi mật khẩu

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->force_password_change = false;
        $user->save();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Mật khẩu đã được thay đổi thành công',
                'redirect' => $user->role === User::ROLE_ADMIN ? route('users.index') : route('employee.profile')
            ]);
        }
        
        if ($user->role === User::ROLE_ADMIN) {
            return redirect()->route('users.index')->with('success', 'Mật khẩu đã được thay đổi thành công');
        } else {
            return redirect()->route('employee.dashboard')->with('success', 'Mật khẩu đã được thay đổi thành công');
        }
    }
    
    // public function troll()
    // {
    //     return view('admin.question');
    // }
}