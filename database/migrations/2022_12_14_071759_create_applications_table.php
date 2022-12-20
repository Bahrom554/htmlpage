<?php

use App\Models\Application;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('definition')->nullable();
            $table->json('certificates')->nullable();
            $table->json('licenses')->nullable();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('error_or_broken')->nullable();
            $table->text('telecommunication_network')->nullable();
            $table->text('provide_cyber_security')->nullable();
            $table->text('threats_to_information_security')->nullable();
            $table->text('consequences_of_an_incident')->nullable();
            $table->text('organizational_and_technical_measures_to_ensure_security')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('status')->default(1);
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
        Schema::dropIfExists('applications');
    }
}
