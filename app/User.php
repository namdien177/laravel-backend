<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\Providers\JWT;

class User extends Authenticatable implements JWT
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

	/**
	 * @param  array $payload
	 *
	 * @return string
	 */
	public function encode(array $payload)
	{
		// TODO: Implement encode() method.
	}

	/**
	 * @param  string $token
	 *
	 * @return array
	 */
	public function decode($token)
	{
		// TODO: Implement decode() method.
	}

	public function  setPasswordAttribute($value){
		$this->attributes['password'] = bcrypt($value);
	}

	public function author(){
		return $this->hasOne('App\author','id','idViewer');
	}

	public function bookmark(){
		return $this->hasMany('App\bookmark','idUser');
	}

	public function user_manga_chap(){
		return $this->hasMany('App\user_manga_chap');
	}
}
