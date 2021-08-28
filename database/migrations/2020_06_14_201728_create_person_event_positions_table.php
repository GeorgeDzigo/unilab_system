<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonEventPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_event_positions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('biostar_event_id')->nullable();
            $table->unsignedBigInteger('event_log_id')->index()->nullable();
            $table->unsignedBigInteger('person_id')->index()->nullable();
            $table->unsignedBigInteger('position_id')->index()->nullable();
            $table->unsignedBigInteger('department_id')->index()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person_event_positions');
    }
}
