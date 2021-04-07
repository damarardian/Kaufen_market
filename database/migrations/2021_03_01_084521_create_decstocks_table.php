<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDecstocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decstocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->bigInteger("barang_id")->unsigned();
            $table->integer("dec_stock");
            $table->timestamps();
            $table->foreign('barang_id')
            ->references('id')
            ->on('barangs')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('decstocks');
    }
}
