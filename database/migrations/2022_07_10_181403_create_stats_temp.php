<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatsTemp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stats_temp', function (Blueprint $table) {
            $table->id();
            $table->foreignId("player_id")
                  ->references("id")
                  ->on("players");
            $table->string("name", 30);
            $table->unsignedInteger("value")
                  ->comment("Assuming stats are only integers, doubles might be necessary");
            $table->boolean("computed")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stats_temp');
    }
}
