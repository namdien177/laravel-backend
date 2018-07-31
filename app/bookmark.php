<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bookmark extends Model
{
    protected $table = 'bookmarks';

    public function user(){
    	return $this->belongsTo('App\User','idUser');
    }

    public function manga(){
    	return $this->belongsTo('App\manga','idManga');
    }
}
