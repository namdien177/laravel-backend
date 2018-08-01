<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//////////////////Manga API//////////////////////////////////////////
Route::get('manga','MangaController@index');        // Display all

Route::get('manga/{id}','MangaController@show');        // Display one
Route::get('manga/{id}/chap', 'MangaController@indexChap'); // display all chap of manga
Route::get('manga/{id}/chap/{idChap}', 'MangaController@showChap');

Route::post('manga/upload', 'MangaController@store') ;   // upload new manga

Route::get('manga/{id}/tags','MangaController@showTags');        // Display tags

Route::get('tags/{id}','TagsController@show');        // Display tags

/////////////////////////LOGIN API////////////////////////////////////
Route::group([

	'middleware' => 'api'

], function ($router) {

	Route::post('login', 'AuthController@login');
	Route::post('logout', 'AuthController@logout');
	Route::post('refresh', 'AuthController@refresh');
	Route::post('me', 'AuthController@me');
	Route::post('signupauthor','AuthController@signupAuthor');
	Route::post('signupviewer','AuthController@signupViewer');
});