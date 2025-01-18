<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organizer; // Import the Organizer model
use App\Models\Events;     // Import the Event model

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an organizer first
        $organizer = Organizer::create([
            'name' => 'Haziq',
            'email' => 'haziq@gmail.com',
            'password' => bcrypt('haziq123'), // Make sure to hash the password
        ]);

        // Then create an event with the organizer_id
        Events::create([
            'name' => 'Test Event',
            'date' => '2025-01-11',
            'location' => 'Haziq Location',
            'status' => 'Active',
            'organizer_id' => $organizer->organizer_id, // Link the event to the created organizer
        ]);
    }
}
