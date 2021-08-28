<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('event_date')->nullable();
            $table->unsignedInteger('person_id')->index()->nullable();
            $table->string('biostar_card_id')->index()->nullable();
            $table->string('biostar_event_id')->index()->nullable();
            $table->string('biostar_reader_id')->index()->nullable();
            $table->tinyInteger('event_type')->nullable();
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
        Schema::dropIfExists('event_logs');
    }
}
