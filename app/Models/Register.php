<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    protected $table = 'register';
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
    ];
}
