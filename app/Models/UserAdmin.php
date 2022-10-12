<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserAdmin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users_admins';

    protected $fillable = [
        'name',
        'login',
        'email',
        'password',
        'is_active'
    ];

    protected $guarded = [];

    public function scopeIsActive($query)
    {
        return $query->where('is_active',1);
    }
}
