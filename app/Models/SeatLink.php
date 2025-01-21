<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeatLink extends Model
{
    use HasFactory;

    // Fillable attributes for mass assignment
    protected $fillable = [
        'events_id',
        'seat_category',
        'rsvp_link',
    ];

    // SeatLink belongs to an event
    public function event()
    {
        return $this->belongsTo(Events::class);
    }
}
