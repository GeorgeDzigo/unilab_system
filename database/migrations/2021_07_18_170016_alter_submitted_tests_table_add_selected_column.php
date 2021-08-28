<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSubmittedTestsTableAddSelectedColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('submitted_tests', function (Blueprint $table) {
            $table->boolean('selection_status')->default(false)->after('competition_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('submitted_tests', function (Blueprint $table) {
            //
        });
    }
}
