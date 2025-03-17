<?php

namespace App\Services;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class DepartmentService extends BaseService
{
    protected ?Department $department = null;

    /**
     * Set department model
     * 
     * @param Department $department
     * @return $this
     */
    public function setDepartment(Department $department)
    {
        $this->department = $department;
        return $this;
    }

    /**
     * @return Department|null
     */
    public function getDepartment(): Department|null
    {
        return $this->department;
    }

    /**
     * Get all departments with users
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllDepartments()
    {
        return Department::query()->with('users')->get();
    }

    /**
     * Get department by ID with users
     *
     * @param int $id
     * @return Department
     */
    public function getDepartmentById(int $id)
    {
        return Department::query()->with('users')->findOrFail($id);
    }

    /**
     * Create new department from request
     *
     * @param Request $request
     * @return Department
     */
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

    /**
     * Update department information
     *
     * @param Request $request
     * @param int $id
     * @return Department
     */
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

    /**
     * Delete department
     *
     * @param int $id
     * @return bool
     */
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