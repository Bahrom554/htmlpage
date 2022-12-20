<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->json('ram')->nullable();
            $table->json('hdd')->nullable();
            $table->json('ssd')->nullable();
            $table->json('cpu')->nullable();
            $table->json('architecture')->nullable();
            $table->json('power')->nullable();
            $table->json('os')->nullable();
            $table->json('version')->nullable();
            $table->json('case')->nullable();
            $table->json('type')->nullable();
            $table->json('slot')->nullable();
            $table->text('definition')->nullable();
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
        Schema::dropIfExists('devices');
    }
}
