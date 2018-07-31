<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class manga extends Model
{
    protected $table = 'mangas';

    public function manga_alias(){
    	return $this->hasMany('App\manga_alias');
    }

    public function bookmark(){
    	return $this->hasMany('App\bookmark','id','idManga');
    }

    public function manga_author(){
    	return $this->hasMany('App\manga_author','id','idManga');
    }

    public function manga_chap(){
    	return $this->hasMany('App\manga_chap','id','idManga');
    }

    public function manga_tag(){
    	return $this->hasMany('App\manga_tags','id','idManga');
    }

    public function user_mang_chap(){
    	return $this->hasMany('App\user_manga_chap','id','idManga');
    }
}
