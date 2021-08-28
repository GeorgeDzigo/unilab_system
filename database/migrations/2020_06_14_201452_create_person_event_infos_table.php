<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonEventInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_event_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('event_log_id')->index()->nullable();
            $table->unsignedBigInteger('person_id')->index()->nullable();
            $table->string('biostar_event_id')->nullable();
            $table->string('personal_number')->nullable();
            $table->string('card_id')->nullable();
            $table->string('biostar_card_id')->nullable();
            $table->json('additional_info')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('unilab_email')->nullable();
            $table->string('personal_email')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('person_event_infos');
    }
}
