<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class Admin extends Model implements Authenticatable
{
    use HasFactory, AuthenticableTrait;

    // Define the custom primary key
    protected $primaryKey = 'admin_id';

    // Define which fields are mass assignable
    protected $fillable = ['name', 'email', 'password'];

    // Set incrementing to false if you're not using auto-increment (not necessary here, but good to know)
    public $incrementing = true;

    // Define the table name if it's not the plural of the model name
    protected $table = 'admins';
}
