<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelecommunicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telecommunications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('network_topology')->nullable();
            $table->unsignedBigInteger('contract')->nullable();
            $table->boolean('connect_net')->default(false);
            $table->boolean('connect_nat')->default(false);
            $table->integer('points_connect_net')->default(0);
            $table->integer('provider_count')->default(0);
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
        Schema::dropIfExists('telecommunications');
    }
}
