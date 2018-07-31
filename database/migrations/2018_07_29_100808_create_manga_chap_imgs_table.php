<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMangaChapImgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manga_chap_imgs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idChap');
            $table->string('img_url',200);
            $table->timestamps();

//            $table->primary(['id','idChap']);
//            $table->foreign('idChap')->references('id')->on('manga_chaps')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manga_chap_imgs');
    }
}
