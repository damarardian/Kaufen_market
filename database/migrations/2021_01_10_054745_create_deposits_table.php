<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId("data_id");
            $table->integer("total");    
            // $table->string("waiting_confirmation");    
            $table->bigInteger('user_id')->unsigned();     
            $table->timestamps();      
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade')              
            ->onUpdateelete('cascade');              
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deposits');
    }
}
