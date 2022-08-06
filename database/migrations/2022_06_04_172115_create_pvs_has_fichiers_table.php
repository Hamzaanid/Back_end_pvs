<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePvsHasFichiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pvs_has_fichiers', function (Blueprint $table) {
            $table->id();
            $table->string('lien');
            $table->foreignId('pvsID')->constrained('pvs')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->string('name');
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
        Schema::dropIfExists('pvs_has_fichiers');
    }
}
