<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends BaseModelAuth
{
    use HasFactory, Notifiable, HasRoles, HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'document',
        'address',
        'email',
        'password',
        'profile',
        'phone',
        'status',
        'image',
        'session_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function Sale()
    {
        $this->hasMany(Sale::class);
    }

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->last_name;
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'employee_id')
            ->orderBy('created_at', 'desc');
    }
}
