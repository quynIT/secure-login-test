<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
class UserController extends Controller
{
    /**
     * Hiển thị danh sách nhân viên.
     */
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

    /**
     * Lưu nhân viên mới vào database.
     */
    public function store(UserRequest $request)
    {
        $user = UserService::getInstance()->createUser($request);
        
        if ($request->expectsJson()) {
            return response()->json($user, 201);
        }
        
        return redirect()->route('users.index')->with('success', 'Thêm nhân viên thành công!');
    }

    /**
     * Hiển thị thông tin chi tiết của nhân viên.
     */
    public function show($id)
    {
        $user = User::with('department')->findOrFail($id);
        
        if (request()->expectsJson()) {
            return response()->json($user);
        }
        
        return view('users.show', compact('user'));
    }

    /**
     * Hiển thị form chỉnh sửa thông tin nhân viên.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $departments = Department::all();
        
        return view('users.edit', compact('user', 'departments'));
    }

    /**
     * Cập nhật thông tin nhân viên vào database.
     */
    public function update(UserRequest $request, $id)
    {
        $user = UserService::getInstance()->updateUser($request, $id);

        if ($request->expectsJson()) {
            return response()->json($user);
        }
        
        return redirect()->route('users.index')->with('success', 'Cập nhật thông tin nhân viên thành công!');
    }

    /**
     * Xóa nhân viên khỏi database.
     */
    public function destroy($id)
    {
        UserService::getInstance()->deleteUser($id);

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Xóa nhân viên thành công!']);
        }
        
        return redirect()->route('users.index')->with('success', 'Xóa nhân viên thành công!');
    }
    public function resetPassword(Request $request)
    {
    $request->validate([
        'user_ids' => 'required|array',
        'user_ids.*' => 'integer|exists:users,id'
    ]);
    
    $results = UserService::getInstance()->resetMultiplePasswords($request->user_ids);
    
    if ($request->expectsJson()) {
        return response()->json(['message' => 'Reset mật khẩu thành công', 'results' => $results]);
    }
    
    return redirect()->back()->with('success', 'Reset mật khẩu thành công cho ' . count($results) . ' tài khoản');
    }

}