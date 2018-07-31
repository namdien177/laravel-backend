<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tags extends Model
{
    protected $table = 'tags';

    public function manga_tags(){
    	return $this->hasMany(manga_tags::class,'idTag','id');
    }
}
