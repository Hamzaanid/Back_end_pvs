<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDossierEnquetesTable extends Migration
{
    public function up()
    {
        Schema::create('dossier_enquetes', function (Blueprint $table) {
            $table->id();
            $table->string('NumDossier',50)->unique();
            $table->foreignId('type_dossierID')->constrained('type_dossiers');
            $table->enum('chambre_enquete',['الغرفة 1','الغرفة 2']);
            $table->foreignId('juge_enqueteID')->constrained('users');
            $table->foreignId('userID')->constrained('users');
            $table->foreignId('pvsID')->constrained('pvs');
            $table->string('lien');
            $table->boolean('traiter')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dossier_enquetes');
    }
}
