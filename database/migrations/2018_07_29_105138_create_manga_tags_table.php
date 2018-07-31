<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMangaTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manga_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idManga');
            $table->unsignedInteger('idTag');
            $table->timestamps();

//            $table->primary(['id','idManga','idTag']);

//            $table->foreign('idManga')->references('id')->on('mangas')->onDelete('cascade')->onUpdate('cascade');
//            $table->foreign('idTag')->references('id')->on('tags')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manga_tags');
    }
}
