<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('person_id')->nullable()->unsigned();
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->tinyInteger('action')->nullable()->unsigned();
            $table->bigInteger('item_id')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('person_id')->references('id')->on('people')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('item_id')->references('id')->on('items')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_logs');
    }
}
