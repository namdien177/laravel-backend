<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class manga_chap extends Model
{
    protected $table = 'manga_chaps';
    public function manga(){
    	return $this ->belongsTo('App\manga');
    }
}
