<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmittedTestsAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submitted_tests_answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('submitted_test_id');
            $table->unsignedBigInteger('question_id');
            $table->longText('question_name');
            $table->timestamps();


            $table->foreign('submitted_test_id')
                ->references('id')
                ->on('submitted_tests')
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
        Schema::dropIfExists('submitted_tests_answers');
    }
}
