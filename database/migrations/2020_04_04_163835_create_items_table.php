<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('number')->nullable();
            $table->string('qr_path')->nullable();
            $table->json('attributes')->nullable();
            $table->tinyInteger('status')->default(1)->nullable();
            $table->bigInteger('type_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('type_id')->references('id')->on('item_types')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
