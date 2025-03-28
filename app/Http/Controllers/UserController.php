<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
class UserController extends Controller
{
    
    //Hiển thị danh sách nhân viên.
     
    public function index()
    {
        $users = UserService::getInstance()->getAllUsers();
        $departments = Department::all();
        return view('admin.users', compact('users', 'departments'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('users.create', compact('departments'));
    }

    
    //Lưu nhân viên mới vào database.
     
    public function store(UserRequest $request)
    {
        $user = UserService::getInstance()->createUser($request);
        return response()->json($user, 201);
        
    }

    
    //Hiển thị thông tin chi tiết của nhân viên.
     
    public function show($id)
    {
        $user = User::query()->with('department')->findOrFail($id);
        
        if (request()->expectsJson()) {
            return response()->json($user);
        }
        return view('users.show', compact('user'));
    }

    
    //Hiển thị form chỉnh sửa thông tin nhân viên.
     
    public function edit($id)
    {
        $user = User::query()->findOrFail($id);
        $departments = Department::all();
        return view('users.edit', compact('user', 'departments'));
    }

    
    //Cập nhật thông tin nhân viên vào database.
     
    public function update(UserRequest $request, User $user)
    {
        $updatedUser = UserService::getInstance()->updateUser($user, $request->validated());
        return response()->json($updatedUser);
    }
    
    
    //Xóa nhân viên khỏi database.
     
    public function destroy(User $user)
    {
    UserService::getInstance()->deleteUser($user);
    return response()->json(['message' => 'Xóa nhân viên thành công!']);
    }


    public function resetPassword(Request $request)
    {
    $request->validate([
        'user_ids' => 'required|array',
        'user_ids.*' => 'integer|exists:users,id'
    ]);
    $results = UserService::getInstance()->resetMultiplePasswords($request->user_ids);
    return response()->json(['message' => 'Reset mật khẩu thành công', 'results' => $results]);
    }

}