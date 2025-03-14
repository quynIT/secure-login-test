<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
class EmployeeController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $departments = Department::all();
        
        return view('employee.profile', compact('user', 'departments'));
    }
    
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'avatar' => 'nullable|image|max:2048',
            // 'current_password' => 'required_with:password|password:web',
            'password' => 'nullable|min:8|confirmed',
        ]);
        if ($request->filled('password')) {
            if (!$request->filled('current_password')) {
                return redirect()->back()->withErrors(['current_password' => 'Vui lòng nhập mật khẩu hiện tại'])->withInput();
            }
            
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác'])->withInput();
            }
        }
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
            $userData['force_password_change'] = false;
        }
        
        if ($request->hasFile('avatar')) {
            // Xóa avatar cũ nếu có
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Lưu avatar mới
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $userData['avatar'] = $avatarPath;
        }
        
        $user->update($userData);
        
        return redirect()->route('employee.profile')->with('success', 'Cập nhật thông tin thành công!');
    }
}