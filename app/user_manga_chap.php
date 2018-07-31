<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class user_manga_chap extends Model
{
    protected $table ='user_manga_chaps';

    public function user(){
    	return $this->belongsTo('App\User','idUser');
    }

    public function manga(){
    	return $this->belongsTo('App\manga','idManga');
    }

    public function manga_chap(){
    	return $this->belongsTo('App\manga_chap','idChap');
    }
}
