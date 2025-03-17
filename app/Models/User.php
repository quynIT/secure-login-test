<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    public const ROLE_ADMIN = 'root';
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role',
        'department_id',
        'force_password_change',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'force_password_change' => 'boolean',
    ];

    /**
     * Quan hệ với phòng ban (Department).
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
