<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class manga_tags extends Model
{
    protected $table = 'manga_tags';

    public function manga(){
    	return $this->hasMany('App\manga');
    }

    public function tags(){
    	return $this->belongsTo(tags::class,'idTag','id');
    }
}
