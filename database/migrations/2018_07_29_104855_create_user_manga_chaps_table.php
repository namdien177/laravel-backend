<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserMangaChapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_manga_chaps', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idUser');
            $table->unsignedInteger('idManga');
            $table->unsignedInteger('idChap');
            $table->timestamps();



//            $table->foreign('idManga')->references('id')->on('mangas')->onDelete('cascade')->onUpdate('cascade');
//            $table->foreign('idUser')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('user_manga_chaps');
    }
}
