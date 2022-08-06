<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaintHasFichiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plaint_has_fichiers', function (Blueprint $table) {
            $table->id();
            $table->string('lien');
            $table->foreignId('plaintID')->constrained('plaints')
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
        Schema::dropIfExists('plaint_has_fichiers');
    }
}
