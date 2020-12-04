<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class  CreateSiparisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siparis', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sepet_id')->unsigned();
            $table->decimal('siparis_tutari',7,3);
            $table->string('durum',30)->nullable();

            $table->string('adsoyad',50)->nullable();
            $table->string('adres',200)->nullable();
            $table->string('telefon',15)->nullable();
            $table->string('ceptelefonu',15)->nullable();
            $table->string('banka',20)->nullable();
            $table->integer('taksit_sayisi')->nullable();

            $table->timestamp('olusturulma_tarihi')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('guncelleme_tarihi')->default(DB::raw('CURRENT_TIMESTAMP on UPDATE CURRENT_TIMESTAMP'));
            //$table->softDeletes();//Kayıt silindiğinde silinme tarihi saklamak için gerekli kolonu oluşturur.
            $table->timestamp('silinme_tarihi')->nullable();

            $table->unique('sepet_id');
            $table->foreign('sepet_id')->references('id')->on('sepet')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siparis');
    }
}
