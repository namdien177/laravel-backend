<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class author extends Model
{
    protected $table = 'authors';

    public function manga_author(){
    	return $this->hasMany('App\manga_author','idAuthor','id');
    }

    public function User(){
    	return $this->hasOne('App\User','id','idViewer');
    }
}
