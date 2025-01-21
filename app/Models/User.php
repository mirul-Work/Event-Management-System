<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    // public function events()
    // {
    //     return $this->hasMany(Event::class, 'organizer_id');
    // }

    public function events()
    {
        return $this->hasMany(Events::class);
    }
    //untuk check role
    // public function hasRole($role)
    // {
    //     return $this->role === $role;
    // }

}
