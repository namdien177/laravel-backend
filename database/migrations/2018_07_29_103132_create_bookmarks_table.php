<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookmarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idUser');
            $table->unsignedInteger('idManga');
            $table->timestamps();

//            $table->primary(['id','idUser','idManga']);
//            $table->foreign('idManga')->references('id')->on('mangas')->onDelete('cascade')->onUpdate('cascade');
//            $table->foreign('idUser')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookmarks');
    }
}
