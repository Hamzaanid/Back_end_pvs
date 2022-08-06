<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHasPvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_has_pvs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userID')->constrained('users')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreignId('pvsID')->constrained('pvs')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
           $table->foreignId('traitID')->constrained('traiteds')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

                  $table->string('descision')->nullable();
                  $table->date('dateMission');

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
        Schema::dropIfExists('user_has_pvs');
    }
}
