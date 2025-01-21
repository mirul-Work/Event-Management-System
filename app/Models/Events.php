<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;

    protected $primaryKey = 'id'; // Ensure it's 'id' unless using a custom key.


    // Fillable attributes for mass assignment
    protected $fillable = [
        'user_id',
        'name',
        'date',
        'location',
        'total_seats',
        'regular_seats',
        'vip_seats',
        'vvip_seats',
        'regular_seat_available',
        'vip_seat_available',
        'vvip_seat_available',
        'available_seats',
        'status',
    ];



    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class); // Assuming 'user_id' is the foreign key in the events table
    }

    // Define the relationship between event and attendees // Event has many attendees
    public function attendees()
    {
        return $this->hasMany(Attendee::class, 'events_id'); // Ensure the foreign key is correct
    }

    // Event has many seat links
    public function seatLinks()
    {
        return $this->hasMany(SeatLink::class, 'events_id');
    }

    // Define the forOrganizer scope
    public function scopeForOrganizer($query, $organizer_id)
    {
        return $query->where('organizer_id', $organizer_id);
    }
}
