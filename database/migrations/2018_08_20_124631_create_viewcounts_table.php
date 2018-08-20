<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewcountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('viewcounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idManga');
            $table->unsignedInteger('idViewer');
            $table->unsignedInteger('idChap');
	        $table->string('IPUser',20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('viewcounts');
    }
}
