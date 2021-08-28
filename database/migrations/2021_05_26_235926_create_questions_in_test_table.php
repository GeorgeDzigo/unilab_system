<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsInTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions_in_test', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('test_id');
            $table->unsignedBigInteger('question_id');
            $table->string('sort'); 
            $table->timestamps();

            $table->foreign('test_id')
            ->references('id')
            ->on('tests')
            ->onDelete('cascade');

            $table->foreign('question_id')
            ->references('id')
            ->on('test_questions')
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
        Schema::dropIfExists('questions_in_test');
    }
}
