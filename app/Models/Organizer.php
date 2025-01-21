<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Organizer extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password'];

    // Specify the primary key if it's not the default 'id'
    protected $primaryKey = 'organizer_id';  // Use your actual primary key here

    // Define the relationship
    public function events()
    {
        return $this->hasMany(Events::class, 'organizer_id', 'organizer_id');  // 'organizer_id' in Event model and 'organizer_id' in Organizer model
    }
}
