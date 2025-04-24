<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('strava_id')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type');
            $table->dateTime('start_date');
            $table->float('distance');
            $table->integer('moving_time');
            $table->integer('elapsed_time');
            $table->float('average_speed');
            $table->float('max_speed');
            $table->integer('average_heartrate')->nullable();
            $table->integer('max_heartrate')->nullable();
            $table->float('total_elevation_gain');
            $table->float('elev_high');
            $table->float('elev_low');            
            $table->dateTime('sync_date')->default(now()); 
            $table->integer('kudos_count')->default(0);
            $table->text('description')->nullable();
            $table->integer('calories')->nullable();
            $table->text('map_polyline')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
