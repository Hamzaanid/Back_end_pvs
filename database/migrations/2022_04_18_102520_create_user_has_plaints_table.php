<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHasPlaintsTable extends Migration
{

    public function up()
    {
        Schema::create('user_has_plaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userID')->constrained('users')
                  ->onUpdate('cascade');

            $table->foreignId('plaintID')->constrained('plaints')
                  ->onUpdate('cascade');

            $table->foreignId('traitID')->constrained('traiteds')
                  ->onUpdate('cascade');
                  $table->date('dateMission');
                  $table->string('descision')->nullable();
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
        Schema::dropIfExists('user_has_plaints');
    }
}
