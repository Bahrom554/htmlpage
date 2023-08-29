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
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('staff_id');
            // subject many to many
            $table->unsignedBigInteger('subject_id');

            // МАИ объектининг кўлами ва мақсади;
            $table->unsignedBigInteger('purpose_id');
            // МАИ объектига берилган аҳамиятлилик тоифаси;
            $table->unsignedBigInteger('importance_id');
            $table->unsignedBigInteger('file_id');
            //axborotlashtirish vositasi
            $table->json('information_tool');
            $table->json('cybersecurity_tool');
            $table->unsignedBigInteger('network_id');

            // МАИ объектида киберхавфсизликни таъминлаш бўйича қўлланиладиган чора ва воситалар;
            $table->text('provide_cyber_security')->nullable();
            // МАИ объектига нисбатан ахборот хавфсизлиги таҳдидлари
            //  ва қоидабузарлик тоифалари ҳақида маълумотлар;
            $table->text('threats_to_information_security')->nullable();
            // МАИ объектида киберхавфсизлик инцидентини юз беришининг эҳтимолий оқибатлари;
            $table->text('consequences_of_an_incident')->nullable();
            $table->unsignedInteger('status')->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->softDeletes();
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
