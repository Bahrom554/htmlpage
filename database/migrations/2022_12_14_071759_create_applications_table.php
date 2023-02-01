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
            // subject many to many
            $table->string('subject');
            $table->string('subject_type');
            $table->string('subject_definition')->nullable();
            $table->unsignedBigInteger('subject_document')->nullable();
            // shaxs to'grisidegi malumot
            $table->json('staffs')->nullable();
            // МАИ объектининг кўлами ва мақсади;
            $table->text('scope_and_purpose');
            // МАИ объектига берилган аҳамиятлилик тоифаси;
            $table->unsignedBigInteger('importance_id')->nullable();
            // МАИ объекти фаолиятида хатолик бўлса ёки у ишдан чиққан тақдирда, юзага келиши мумкин //бўлган оқибатлар ва зарар;
            $table->text('error_or_broken')->nullable();
            //МАИ объектида фойдаланиладиган аппарат, дастурий-аппарат 
            //ва дастурий ахборотлаштириш воситалари ҳақида маълумот, шунингдек, уларнинг ахборот хавфсизлигига мувофиқлиги сертификати;
            $table->json('devices')->nullable();
            // МАИ объектида фойдаланиладиган аппарат, дастурий-аппарат 
             //ва дастурий ахборотлаштириш воситалари ҳақида маълумот, шунингдек, уларнинг ахборот хавфсизлигига мувофиқлиги сертификати;
             $table->unsignedInteger('license_id')->nullable();
             $table->unsignedInteger('certificate_id')->nullable();
            //  МАИ объектини умумий телекоммуникация тармоғи, шунингдек, Интернетга уланиш ва фойдаланиш тартиби;
            $table->json('telecommunications')->nullable();
            // МАИ объектида киберхавфсизликни таъминлаш бўйича қўлланиладиган чора ва воситалар;
            $table->text('provide_cyber_security')->nullable();
            // МАИ объектига нисбатан ахборот хавфсизлиги таҳдидлари 
            //  ва қоидабузарлик тоифалари ҳақида маълумотлар;
            $table->text('threats_to_information_security')->nullable();
            // МАИ объектида киберхавфсизлик инцидентини юз беришининг эҳтимолий оқибатлари;
            $table->text('consequences_of_an_incident')->nullable();
            // МАИ объектида хавфсизликни таъминлашнинг ташкилий ва техник чоралари.
            $table->text('organizational_and_technical_measures_to_ensure_security')->nullable();
            $table->unsignedInteger('status')->default(1);
            $table->string('reason')->nullable();
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
