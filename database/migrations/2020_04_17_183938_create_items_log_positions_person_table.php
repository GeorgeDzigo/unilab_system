<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsLogPositionsPersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_log_position_persons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('position_person_id')->unsigned()->nullable();
            $table->bigInteger('item_log_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('position_person_id', 'pos_people_item_log')->references('id')->on('positions_people')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('item_log_id', 'item_log_pers_post')->references('id')->on('item_logs')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items_log_position_persons');
    }
}
