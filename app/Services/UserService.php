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
        return User::query()->with('department')->get();
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
            $validated = $request->only(['name', 'email', 'role', 'department_id', 'password']);
            $password = $validated['password'] ?? Str::random(8);

            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($password),
                'role' => $validated['role'],
                'department_id' => $validated['department_id'],
                'force_password_change' => true,
            ];

            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $userData['avatar'] = $avatarPath;
            }

            $user = User::create($userData);

            Mail::to($user->email)->queue(new WelcomeMail($user, $password));
            
            DB::commit();
            
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
     * Update user information
     *
     * @param Request $request
     * @param int $id
     * @return User
     */
    public function updateUser(User $user, array $data)
{
    DB::beginTransaction();
    
    try {
        $oldAvatar = $user->avatar;
        
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'department_id' => $data['department_id'],
        ];
        
        // Cập nhật mật khẩu nếu có
        if (isset($data['password']) && !empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        // Xử lý upload avatar
        if (isset($data['avatar'])) {
            $avatarPath = $data['avatar']->store('avatars', 'public');
            $userData['avatar'] = $avatarPath;
        }

        $user->update($userData);
        DB::commit();
        
        if ($oldAvatar && isset($data['avatar'])) {
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
    public function deleteUser(User $user)
    {
        try {
            $avatarPath = $user->avatar;
            
            $result = $user->delete();
            if ($avatarPath) {
                Storage::disk('public')->delete($avatarPath);
            }
            
            return $result;
        } catch (\Exception $e) {
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
                $user = User::query()->find($userId);
                
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