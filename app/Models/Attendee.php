<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    use HasFactory;

    // Fillable attributes for mass assignment
    protected $fillable = [
        'events_id',
        'name',
        'email',
        'user_id', // This assumes you are linking attendee to user
        'seat_category',
        'token',
        'status',
    ];

    // Attendee belongs to an event
    public function event()
    {
        return $this->belongsTo(Events::class, 'events_id');
    }

    // Attendee belongs to a user (the person attending)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
