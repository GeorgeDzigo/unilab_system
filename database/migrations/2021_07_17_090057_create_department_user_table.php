<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_user', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->length(20)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->bigInteger('department_id')->unsigned()->length(20)->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
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
        Schema::dropIfExists('department_user');
    }
}
