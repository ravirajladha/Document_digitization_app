<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // public function permissions()
    // {
    //     return $this->belongsToMany(Permission::class, 'user_has_permissions');
    // }

    // User model

// In User model
// public function permissions()
// {
//     return $this->belongsToMany(Permission::class, 'user_has_permissions', 'user_id', 'permission_display_name', 'display_name');
// }


// In your User model
// public function permissions()
// {
//     return $this->belongsToMany(Permission::class, 'user_has_permissions', 'user_id', 'permission_display_name', 'display_name');
// }




// User model
public function permissions()
{
    return $this->belongsToMany(
        Permission::class,
        'user_has_permissions', // Pivot table
        'user_id', // Foreign key on the pivot table for the user
        'permission_display_name', // Foreign key on the pivot table for the permission
        'id', // Local key on the users table
        'display_name' // Local key on the permissions table
    );
}



// In User model
public function hasPermission($permissionName)
{
    if ($this->type === 'admin') {
        return true;
    }

    return $this->permissions()
                ->where('display_name', $permissionName)
                ->exists();
}





// User model



}
