<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('attendees', function (Blueprint $table) {
            $table->boolean('email_sent')->default(false)->after('status');
        });
    }

    public function down()
    {
        Schema::table('attendees', function (Blueprint $table) {
            $table->dropColumn('email_sent');
        });
    }

};
