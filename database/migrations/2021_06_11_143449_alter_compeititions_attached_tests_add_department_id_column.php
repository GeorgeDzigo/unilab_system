<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCompeititionsAttachedTestsAddDepartmentIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('competitions_attached_tests', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->after('competition_id');

            $table->foreign('department_id')
            ->references('id')
            ->on('departments')
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
        Schema::table('competitions_attached_tests', function (Blueprint $table) {
            //
        });
    }
}
