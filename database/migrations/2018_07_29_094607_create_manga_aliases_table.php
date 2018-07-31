<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMangaAliasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manga_aliases', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_manga');
            $table->string('name',200);
            $table->timestamps();

//            $table->foreign('id_manga')->references('id')->on('mangas')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manga_aliases');
    }
}
