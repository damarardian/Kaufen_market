<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKoperasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()    
    {    
        Schema::create('koperasis', function (Blueprint $table) {
            $table->id();      
            $table->foreignId('user_id');
            $table->string('name', 100);
            $table->string('jenis',100);
            $table->string('stock', 191);
            $table->string('harga', 191);
            $table->string('image')->nullable();
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
        Schema::dropIfExists('koperasis');
    }
}
