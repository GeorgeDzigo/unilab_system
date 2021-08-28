<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetitionDepartmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competition_department', function (Blueprint $table) {
            $table->bigInteger('competition_id')->unsigned()->length(20)->nullable();
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('set null');
            $table->bigInteger('department_id')->unsigned()->length(20)->nullable(); 
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null') ;
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
        Schema::dropIfExists('competition_department');
    }
}
