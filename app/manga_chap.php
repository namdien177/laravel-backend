<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class manga_chap extends Model
{
    protected $table = 'manga_chaps';
    public function manga(){
    	return $this ->belongsTo('App\manga','idManga','id');
    }

    public function manga_chap_img(){
    	return $this->hasMany('App\manga_chap_img','id','idChap');
    }

	public function viewcount(){
		return $this->hasMany('App\viewcount','idChap','id');
	}
}
