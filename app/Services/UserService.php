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
    public function updateProfile(User $user, Request $request, bool $isAdmin = false)
    {
        DB::beginTransaction();
        
        try {
            $oldAvatar = $user->avatar;
            
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];
            
            // Cập nhật mật khẩu nếu có
            if ($request->filled('password')) {
                // Kiểm tra mật khẩu hiện tại nếu không phải admin
                if (!$isAdmin && !Hash::check($request->current_password, $user->password)) {
                    throw new \Exception('Mật khẩu hiện tại không chính xác');
                }
                
                $userData['password'] = Hash::make($request->password);
                $userData['force_password_change'] = false;
            }
            
            // Nếu là admin, có thể cập nhật thêm thông tin khác
            if ($isAdmin && $request->filled('role')) {
                $userData['role'] = $request->role;
            }
            
            if ($isAdmin && $request->filled('department_id')) {
                $userData['department_id'] = $request->department_id;
            }
            
            // Xử lý upload avatar
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $userData['avatar'] = $avatarPath;
            }
            
            $user->update($userData);
            DB::commit();
            
            // Xóa avatar cũ nếu đã upload avatar mới
            if ($oldAvatar && $request->hasFile('avatar')) {
                Storage::disk('public')->delete($oldAvatar);
            }
            
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Xóa avatar mới nếu có lỗi
            if (isset($avatarPath) && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }
            
            throw $e;
        }
    }
}