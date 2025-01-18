<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendeesTable extends Migration
{
    public function up()
    {
        Schema::create('attendees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('events_id')
                ->constrained('events') // Foreign key to the events table
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users') // Foreign key to the users table
                ->onDelete('cascade');
            $table->string('name'); //nama untuk attendee
            $table->string('email'); // Seat category (regular, VIP, VVIP)
            $table->enum('seat_category', ['regular', 'vip', 'vvip']); // Seat category (regular, VIP, VVIP)
            $table->enum('status', ['accepted', 'rejected', 'pending'])->default('pending'); // RSVP status
            $table->string('token', 64)->unique(); // Unique token for RSVP
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendees');
    }
}
