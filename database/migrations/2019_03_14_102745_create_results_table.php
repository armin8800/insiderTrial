<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('home_team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->integer('guest_team_id')->references('id')->on('teams')->onDelete('cascade');;
            $table->integer('home_team_result')->nullable();
            $table->integer('guest_team_result')->nullable();
            $table->integer('week_played')->nullable();
            $table->integer('is_played')->default(0);
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
        Schema::dropIfExists('results');
    }
}
