<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatsTable extends Migration
{
    public function up()
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('events_id')->constrained()->onDelete('cascade');
            $table->string('seat_category'); // e.g., regular, vip, vvip
            $table->string('seat_number')->unique(); // Unique seat identifier
            $table->enum('status', ['available', 'reserved', 'booked'])->default('available');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('seats');
    }
}
