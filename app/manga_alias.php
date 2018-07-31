<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class manga_alias extends Model
{
	protected $table = 'manga_aliases';

	public function manga(){
		return $this->belongsTo('App\manga', 'idManga');
	}
}
