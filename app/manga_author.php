<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class manga_author extends Model
{
    protected $table = 'manga_authors';

    public function manga(){
    	return $this->hasMany('App\manga','idManga','id');
    }

    public function author(){
    	return $this->belongsTo('App\author','id','idAuthor');
    }
}
