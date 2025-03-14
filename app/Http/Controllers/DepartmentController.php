<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Http\Requests\DepartmentRequest;
use App\Services\DepartmentService;

class DepartmentController extends Controller
{
    /**
     * Hiển thị danh sách phòng ban.
     */
    public function index()
    {
        $departments = DepartmentService::getInstance()->getAllDepartments();
        
        return view('admin.departments', compact('departments'));
    }

    /**
     * Lưu phòng ban mới vào database.
     */
    public function store(DepartmentRequest $request)
    {
        $department = DepartmentService::getInstance()->createDepartment($request);
        
        return response()->json($department, 201);
    }

    /**
     * Hiển thị thông tin chi tiết của phòng ban.
     */
    public function show($id)
    {
        $department = DepartmentService::getInstance()->getDepartmentById($id);
        
        return response()->json($department);
    }

    /**
     * Cập nhật thông tin phòng ban vào database.
     */
    public function update(DepartmentRequest $request, $id)
    {
        $department = DepartmentService::getInstance()->updateDepartment($request, $id);
        
        return response()->json($department);
    }

    /**
     * Xóa phòng ban khỏi database.
     */
    public function destroy($id)
    {
        DepartmentService::getInstance()->deleteDepartment($id);
        
        return response()->json(['message' => 'Department deleted successfully']);
    }
}