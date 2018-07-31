<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMangaAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manga_authors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idManga');
            $table->unsignedInteger('idAuthor');
            $table->timestamps();

//            $table->primary(['id','idManga','idAuthor']);
//            $table->foreign('idManga')->references('id')->on('mangas')->onDelete('cascade')->onUpdate('cascade');
//            $table->foreign('idAuthor')->references('id')->on('authors')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manga_authors');
    }
}
