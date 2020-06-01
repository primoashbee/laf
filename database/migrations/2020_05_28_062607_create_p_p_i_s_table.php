<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePPISTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ppi_answers', function (Blueprint $table) {
            $table->id();
            
            $table->string('ppi_q_1')->nullable();
            $table->string('ppi_q_2')->nullable();
            $table->string('ppi_q_3')->nullable();
            $table->string('ppi_q_4')->nullable();
            $table->string('ppi_q_5')->nullable();
            $table->string('ppi_q_6')->nullable();
            $table->string('ppi_q_7')->nullable();
            $table->string('ppi_q_8')->nullable();
            $table->string('ppi_q_9')->nullable();
            $table->string('ppi_q_10')->nullable();


            $table->unsignedInteger('client_id');
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
        Schema::dropIfExists('p_p_i_s');
    }
}
