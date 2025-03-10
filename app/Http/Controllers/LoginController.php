<?php

namespace App\Http\Controllers;

use App\Models\Register;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('register.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        // Tìm người dùng theo email
        $user = Register::where('email', $request->email)->first();
        
        // Kiểm tra xem người dùng tồn tại và mật khẩu đúng
        if ($user && Hash::check($request->password, $user->password)) {
            // Tạo session đăng nhập
            $request->session()->put('user_id', $user->id);
            $request->session()->put('user_name', $user->name);
            $request->session()->put('user_email', $user->email);
            
            // Nếu người dùng chọn "Ghi nhớ đăng nhập"
            if ($request->remember) {
                $minutes = 60 * 24 * 30; // 30 ngày
                cookie('user_email', $user->email, $minutes);
                cookie('user_remember', '1', $minutes);
            }
            
            // Chuyển hướng người dùng sau khi đăng nhập thành công
            return response()->json([
                'success' => true,
                'redirect' => '/foods'
            ]);
        }
        
        // Đăng nhập thất bại
        return response()->json([
            'success' => false,
            'errors' => [
                'general' => ['Email hoặc mật khẩu không đúng']
            ]
        ], 422);
    }
    
    public function logout(Request $request)
    {
        // Xóa session đăng nhập
        $request->session()->forget(['user_id', 'user_name', 'user_email']);
        $request->session()->flush();
        
        // Xóa cookie nếu có
        cookie('user_email', '', -1);
        cookie('user_remember', '', -1);
        
        return redirect('/');
    }
}