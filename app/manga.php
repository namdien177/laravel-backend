<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class manga extends Model
{
    protected $table = 'mangas';

    public function manga_alias(){
    	return $this->hasMany('App\manga_alias','idManga','id');
    }

    public function bookmark(){
    	return $this->hasMany('App\bookmark','idManga','id');
    }

    public function manga_author(){
    	return $this->hasMany('App\manga_author','idManga','id');
    }

    public function manga_chap(){
    	return $this->hasMany('App\manga_chap','idManga','id');
    }

    public function manga_tag(){
    	return $this->hasMany('App\manga_tags','idManga','id');
    }

    public function user_mang_chap(){
    	return $this->hasMany('App\user_manga_chap','idManga','id');
    }
}
