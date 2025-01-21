<?php
use Illuminate\Database\Seeder;
use App\Models\Seat;

class SeatSeeder extends Seeder
{
    public function run()
    {
        $categories = ['regular', 'vip', 'vvip'];
        $eventId = 1; // Replace with dynamic event ID

        foreach ($categories as $category) {
            $totalSeats = $category === 'regular' ? 100 : ($category === 'vip' ? 50 : 20);

            for ($i = 1; $i <= $totalSeats; $i++) {
                Seat::create([
                    'events_id' => $eventId,
                    'seat_category' => $category,
                    'seat_number' => strtoupper($category) . '-' . $i,
                    'status' => 'available',
                ]);
            }
        }
    }
}
