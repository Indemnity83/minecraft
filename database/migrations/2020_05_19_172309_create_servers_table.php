<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('profile')->default('vanilla');
            $table->string('version');
            $table->integer('port')->default(25565);
            $table->string('status')->nullable();
            $table->boolean('auto_restart')->default(false);
            $table->boolean('auto_start')->default(true);
            $table->integer('start_retries')->default(1);
            $table->integer('java_xmx')->default('1024');
            $table->integer('java_xms')->default('1024');
            $table->string('java_args')->nullable();
            $table->string('jar_file')->default('minecraft_server.jar');
            $table->string('jar_args')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servers');
    }
}
