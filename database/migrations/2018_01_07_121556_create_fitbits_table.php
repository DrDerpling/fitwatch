<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFitbitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fitbits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');

            $table->boolean('active')->default(false);

            $table->string('fitbit_account_id')->nullable();
            $table->text('access_token')->nullable();
            $table->string('refresh_token')->nullable();

            $table->timestamp('last_sync_date')->useCurrent();
            $table->timestamp('expire_date')->nullable();
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
        Schema::dropIfExists('fitbits');
    }
}
