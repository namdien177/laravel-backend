<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $string1, $idUser)
 */
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
