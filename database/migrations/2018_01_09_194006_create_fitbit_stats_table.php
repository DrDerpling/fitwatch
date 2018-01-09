<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFitbitStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fitbit_stats', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id');
            $table->integer('age');
            $table->integer('height');
            $table->integer('average_daily_steps');
            $table->integer('weight');

            $table->string('birthday');
            $table->string('gender');
            $table->string('avatar');

            $table->text('about_me');
            $table->timestamp('member_since')->useCurrent();

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
        Schema::dropIfExists('fitbit_stats');
    }
}
