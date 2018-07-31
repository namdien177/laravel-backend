<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class manga_chap_img extends Model
{
	protected $table = 'manga_chap_imgs';

	public function manga_chap(){
		return $this->belongsTo('App\manga_chap');
	}

}
