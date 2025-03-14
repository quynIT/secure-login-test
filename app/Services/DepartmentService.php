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
        return Department::with('users')->get();
    }

    /**
     * Get department by ID with users
     *
     * @param int $id
     * @return Department
     */
    public function getDepartmentById(int $id)
    {
        return Department::with('users')->findOrFail($id);
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
            $validated = $request->all();
            
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
    public function updateDepartment(Request $request, int $id)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->all();
            $department = Department::findOrFail($id);
            
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
    public function deleteDepartment(int $id)
    {
        DB::beginTransaction();
        
        try {
            $department = Department::findOrFail($id);
            if ($department->users()->count() > 0) {
                throw new \Exception('Cannot delete department with associated users');
            }
            
            $result = $department->delete();
            DB::commit();
            
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            
            throw $e;
        }
    }
}