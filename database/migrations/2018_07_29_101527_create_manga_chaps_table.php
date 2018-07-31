<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMangaChapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manga_chaps', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idManga');
            $table->double('chap');
            $table->string('title');
            $table->timestamps();

//            $table->primary(['id','idManga']);
//            $table->foreign('idManga')->references('id')->on('mangas')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manga_chaps');
    }
}
