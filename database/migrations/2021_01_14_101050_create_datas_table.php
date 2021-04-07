<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datas', function (Blueprint $table) { 
            // $table->bigIncrements('id');
            $table->engine = 'InnoDB';
            $table->id();
            $table->string("name", 191)->unique();
            $table->string("alamat");
            $table->string("no_telp");
            $table->dateTime("tanggal_lahir");
            // $table->string("posisi", 191);
            $table->bigInteger("user_id")->unsigned();     
            $table->timestamps();
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datas');
    }
}
