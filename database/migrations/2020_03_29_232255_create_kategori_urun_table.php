<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategoriUrunTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kategori_urun', function (Blueprint $table) {
            $table->increments('id');
            //unsigned=>negatif değerleri kullanmamak için kullanılır
            $table->integer('kategori_id')->unsigned();
            $table->integer('urun_id')->unsigned();
            //foreign=>tabloya foreignkey tanımlar hangi kolon için
            //references=>referans gösterilen tabloya
            //on=>referans gösterilen tabloyu
            //onDelete=>
            $table->foreign('kategori_id')->references('id')->on('kategori')->onDelete('cascade');
            $table->foreign('urun_id')->references('id')->on('urun')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kategori_urun');
    }
}
