<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pvs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('TypeSourcePvsID')->constrained('type_source_pvs')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreignId('typepvsID')
                   ->constrained('typepvs')
                   ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('typePoliceJudicID')->constrained('type_police_judics')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->string('Numpvs');
            $table->date('dateEnregPvs');
            $table->string('sujetpvs',150);


            $table->string('policeJudics',100)->nullable();
            $table->date('datePvs')->nullable();
            $table->time('heureRealisation')->nullable();
            $table->boolean('contreInnconue')->nullable();

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
        Schema::dropIfExists('pvs');
    }
}
