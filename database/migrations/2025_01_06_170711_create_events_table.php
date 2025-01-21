<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            // Foreign key to users table for the organizer (user who is creating the event)
            $table->foreignId('user_id')
                  ->constrained('users') // Ensures foreign key is linked to the 'users' table
                  ->onDelete('cascade'); // If the user is deleted, delete the event
            $table->string('name'); // Event name
            $table->date('date'); // Event date and time
            $table->string('location'); // Event location
            $table->integer('total_seats'); // Total number of seats (including VIP and VVIP)
            $table->integer('regular_seats')->default(0); // Number of VVIP seats
            $table->integer('vip_seats')->default(0); // Number of VIP seats
            $table->integer('vvip_seats')->default(0); // Number of VVIP seats
            $table->integer('regular_seat_available')->default(0);
            $table->integer('vip_seat_available')->default(0);
            $table->integer('vvip_seat_available')->default(0);
            $table->integer('available_seats')->default(0); // Total available seats
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Event status (Pending, Approved, Rejected)
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
