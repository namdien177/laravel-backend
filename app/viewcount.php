<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class viewcount extends Model
{
    protected $primaryKey = 'id';

    public function manga(){
    	return $this->belongsTo('App\manga', 'idManga','id');
    }

    public function User(){
    	return $this->belongsTo('App\User','idViewer','id');
    }

    public function manga_chap(){
    	return $this->belongsTo('App\manga_chap','idChap','id');
    }
}
