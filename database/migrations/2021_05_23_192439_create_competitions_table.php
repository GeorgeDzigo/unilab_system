<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title'); 
            $table->biginteger('competition_type_id')->unsigned()->length(20)->nullable();
            $table->foreign('competition_type_id')->references('id')->on('competition_types')->onDelete('set null')  ;
            $table->dateTime('start_date'); 
            $table->dateTime('end_date'); 
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
        Schema::dropIfExists('competitions');
    }
}
