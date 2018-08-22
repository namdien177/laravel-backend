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
///////////////////Author API////////////////////////////////////////
Route::get('author', 'AuthorController@index');
Route::post('author','AuthorController@validateUser');
Route::get('author/{id}', 'AuthorController@show');
Route::get('author/{id}/recent-update', 'AuthorController@indexRecentManga' );

//////////////////Manga API//////////////////////////////////////////
Route::get('manga','MangaController@index');        // Display all
Route::get('manga/updatelist/{number}', 'MangaController@getupdatemanga');
Route::get('manga/hottest/{number}','MangaController@getHottestManga');
Route::get('manga/hottest/{number}/author/{id}','MangaController@getHottestMangaAuthor');

Route::get('manga/{id}','MangaController@show');        // Display one
Route::get('manga/{id}/getauthor', 'MangaController@indexAuthor');   // get author
Route::get('manga/{id}/getview', 'MangaController@getViewCountAll'); // display total view
Route::get('manga/{id}/chap', 'MangaController@indexChap'); // display 75 chap of manga
Route::get('manga/{id}/chap/{idChap}', 'MangaController@showChap');
Route::get('manga/{id}/chap/{idChap}/link', 'MangaController@getmorelinkChap');
Route::get('manga/{id}/chap/{idChap}/count/getcount', 'MangaController@getViewCount');
Route::post('manga/{id}/chap/{idChap}/count/{idViewer}', 'MangaController@addCountView');

Route::get('manga/{id}/tags','MangaController@showTags');        // Display tags

Route::get('manga/{id}/bookmark/{idUser}', 'MangaController@bookmarkManga');    // add to bookmark
Route::get('manga/{id}/unbookmark/{idUser}', 'MangaController@unbookmarkManga');    // add to bookmark

Route::get('manga/{id}/read/{idUser}','MangaController@markRead');
Route::get('manga/{id}/unread/{idUser}','MangaController@markunRead');

Route::get('tags/search','TagsController@searchString');
Route::get('tags/{id}','TagsController@show');        // Display tags

Route::post('content/upload', 'MangaController@store') ;   // upload new manga
/////////////////////////LOGIN API////////////////////////////////////
Route::group([

	'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

	Route::post('login', [ 'as' => 'login', 'uses' => 'AuthController@login']);
	Route::post('logout', [ 'as' => 'logout', 'uses' => 'AuthController@logout']);
	Route::post('refresh', 'AuthController@refresh');
	Route::post('me', 'AuthController@me');
	Route::post('signupauthor','AuthController@signupAuthor');
	Route::post('signupviewer','AuthController@signupViewer');
//	Route::post('signup','AuthController@signup');

});

Route::get('user/{id}/bookmark', 'UserController@showBookmark');    // get bookmark list
Route::post('/anonymous','UserController@anonymousToken');      //record anonymous user