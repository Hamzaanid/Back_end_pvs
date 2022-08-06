<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
//use Illuminate\Database\Schema\Blueprint;

class CreatePlaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plaints', function (Blueprint $table) {
            $table->id();//'idPlaints');
            $table->boolean('contreInconnu')->nullable();

            $table->foreignId('TypePlaintID')
                   ->constrained('type_plaints')
                   ->onUpdate('cascade')
                   ->onDelete('cascade');

            $table->foreignId('SourcePlaintID')
                   ->constrained('source_plaints')
                   ->onUpdate('cascade')
                   ->onDelete('cascade');
            //$table->foreign('SourcePlaintID')->references('idSourcePlaints')->on('source_plaints');
            $table->string('referencePlaints',45);
            $table->date('datePlaints')->nullable();
            $table->date('dateEnregPlaints');
            $table->date('dateFaits')->nullable();
            $table->string('EmplaceFaits',45)->nullable();
            $table->string('sujetPlaints',45);
            $table->timestamps();
        });
        Schema::table('plaints', function (Blueprint $table) {

            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plaints');
    }
}
