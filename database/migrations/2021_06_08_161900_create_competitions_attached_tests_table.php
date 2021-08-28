<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetitionsAttachedTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competitions_attached_tests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('competition_id');
            $table->unsignedBigInteger('test_id');
            $table->timestamps();

            $table->foreign('competition_id')
            ->references('id')
            ->on('competitions')
            ->onDelete('cascade');


            $table->foreign('test_id')
            ->references('id')
            ->on('tests')
            ->onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competitions_attached_tests');
    }
}
