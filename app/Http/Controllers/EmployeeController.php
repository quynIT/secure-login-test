<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use App\Services\UserService;
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
    
    public function updateProfile(ProfileUpdateRequest $request)
    {
        try {
            $user = Auth::user();
            UserService::getInstance()->updateProfile($user, $request);
            
            return redirect()->route('employee.profile')->with('success', 'Cập nhật thông tin thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}