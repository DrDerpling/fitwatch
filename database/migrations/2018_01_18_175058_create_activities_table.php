<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->float('calories');
            $table->float('calories_BMR');
            $table->float('floors');
            $table->float('elevation');
            $table->float('minutes_sedentary');
            $table->float('minutes_lightly_active');
            $table->float('minutes_fairly_active');
            $table->float('minutes_very_active');
            $table->float('activity_calories');
            $table->morphs('activitieble');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
