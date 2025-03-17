<?php

namespace App\Services;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class DepartmentService extends BaseService
{
    protected ?Department $department = null;

    public function setDepartment(Department $department)
    {
        $this->department = $department;
        return $this;
    }

  
    public function getDepartment(): Department|null
    {
        return $this->department;
    }

   
    public function getAllDepartments()
    {
        return Department::query()->with('users')->get();
    }

    
    public function getDepartmentById(int $id)
    {
        return Department::query()->with('users')->findOrFail($id);
    }

   
    public function createDepartment(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->only(['name', 'description']);
            
            $department = Department::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
            ]);
            DB::commit();
            
            return $department;
        } catch (\Exception $e) {
            DB::rollBack();
            
            throw $e;
        }
    }

    
    public function updateDepartment(Department $department, Request $request)
    {
    DB::beginTransaction();
    
    try {
        $validated = $request->only(['name', 'description']);
        
        $department->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);
        DB::commit();
        
        return $department;
    } catch (\Exception $e) {
        DB::rollBack();
        
        throw $e;
    }
    }

    
    public function deleteDepartment(Department $department)
    {
    try {
        if ($department->users()->count() > 0) {
            throw new \Exception('Cannot delete department with associated users');
        }
        $result = $department->delete();
        return $result;
        
    } catch (\Exception $e) {
        throw $e;
    }
    }
}