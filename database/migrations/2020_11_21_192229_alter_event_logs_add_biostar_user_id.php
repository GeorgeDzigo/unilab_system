<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEventLogsAddBiostarUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('biostar_user_id')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_logs', function (Blueprint $table) {
            $table->dropColumn([
                'biostar_user_id'
            ]);
        });
    }
}
