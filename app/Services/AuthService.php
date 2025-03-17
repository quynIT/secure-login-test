<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthService extends BaseService
{
    /**
     * Xác thực và đăng nhập người dùng
     * 
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->only(['email', 'password', 'remember']), [
            'email' => 'required|email',
            'password' => 'required',
            'remember' => 'nullable|boolean'
        ]);
        
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
        
        $credentials = $validator->validated();
        
        // Attempt login
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $request->remember)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Check if user needs to change password
            if ($user->force_password_change) {
                return [
                    'success' => true,
                    'redirect' => route('password.change')
                ];
            }
            
            // Redirect based on role
            if ($user->role === User::ROLE_ADMIN) {
                return [
                    'success' => true,
                    'redirect' => route('users.index')
                ];
            } else {
                return [
                    'success' => true,
                    'redirect' => route('employee.profile')
                ];
            }
        }
        
        // If login failed
        throw ValidationException::withMessages([
            'email' => ['Email hoặc mật khẩu không đúng.']
        ]);
    }
    
    /**
     * Đăng xuất người dùng
     * 
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
    
    /**
     * Thay đổi mật khẩu người dùng
     * 
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->only(['password', 'password_confirmation']), [
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
        
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->force_password_change = false;
        $user->save();
        
        return [
            'success' => true,
            'message' => 'Mật khẩu đã được thay đổi thành công',
            'redirect' => $user->role === User::ROLE_ADMIN ? route('users.index') : route('employee.profile'),
            'user' => $user
        ];
    }
}