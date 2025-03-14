<?php

namespace App\Services;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserService extends BaseService
{
    protected ?User $user = null; // Khởi tạo giá trị mặc định là null

    /**
     * Set user model
     * 
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): User|null
    {
        return $this->user;
    }

    /**
     * Get all users with department
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllUsers()
    {
        return User::with('department')->get();
    }

    /**
     * Create new user from request
     *
     * @param Request $request
     * @return User
     */
    public function createUser(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->all();
            $password = $validated['password'] ?? Str::random(8);

            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($password),
                'role' => $validated['role'],
                'department_id' => $validated['department_id'],
                'force_password_change' => true,
            ];

            // Xử lý upload avatar nếu có
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $userData['avatar'] = $avatarPath;
            }

            $user = User::create($userData);
            
            // Queue email to avoid transaction timeout
            Mail::to($user->email)->queue(new WelcomeMail($user, $password));
            
            // If we reach here, everything went well, commit the transaction
            DB::commit();
            
            return $user;
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollBack();
            
            // Remove uploaded avatar if it exists
            if (isset($avatarPath) && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }
            
            throw $e;
        }
    }

    /**
     * Update user information
     *
     * @param Request $request
     * @param int $id
     * @return User
     */
    public function updateUser(Request $request, int $id)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->all();
            $user = User::findOrFail($id); // Lấy user từ ID
            $oldAvatar = $user->avatar;
            
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'department_id' => $validated['department_id'],
            ];
            
            // Cập nhật mật khẩu nếu có
            if (isset($validated['password']) && !empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            // Xử lý upload avatar
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $userData['avatar'] = $avatarPath;
            }

            $user->update($userData);
            DB::commit();
            if ($oldAvatar && $request->hasFile('avatar')) {
                Storage::disk('public')->delete($oldAvatar);
            }
            
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($avatarPath) && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }
            
            throw $e;
        }
    }

    /**
     * Delete user and associated avatar
     *
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id)
    {
        // Start transaction
        DB::beginTransaction();
        
        try {
            $user = User::findOrFail($id);
            $avatarPath = $user->avatar;
            
            $result = $user->delete();
            DB::commit();
            if ($avatarPath) {
                Storage::disk('public')->delete($avatarPath);
            }
            
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            
            throw $e;
        }
    }
    public function resetMultiplePasswords(array $userIds)
    {
        $results = [];
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            foreach ($userIds as $userId) {
                $user = User::find($userId);
                
                if ($user) {
                    $newPassword = Str::random(8);
                    $user->password = Hash::make($newPassword);
                    $user->force_password_change = true;
                    $user->save();
                    
                    // Queue email to avoid transaction timeout
                    Mail::to($user->email)->queue(new ResetPasswordMail($user, $newPassword));
                    
                    $results[$userId] = [
                        'success' => true,
                        'user' => $user->name,
                        'email' => $user->email
                    ];
                } else {
                    $results[$userId] = [
                        'success' => false,
                        'message' => 'Không tìm thấy người dùng'
                    ];
                }
            }
            
            // If we reach here, commit the transaction
            DB::commit();
            
            return $results;
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollBack();
            
            throw $e;
        }
    }
}