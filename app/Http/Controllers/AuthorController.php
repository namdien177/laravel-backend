<?php

namespace App\Http\Controllers;

use App\author;
use App\Http\Resources\Manga as MangaResource;
use App\manga;
use App\User;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(){
	    $author = author::orderBy('name','asc')->paginate(24);
	    return MangaResource::collection($author);
    }

    public function validateUser(Request $request){
    	$idUser = $request->input('id');
    	$author = author::where('idViewer','=',$idUser)->count();
    	if ($author > 0){
    		return response()->json([
    			'id' => author::where('idViewer','=',$idUser)->first()->id
		    ]);
	    }
	    return response()->json([
		    'id' => -1
	    ]);
    }

    public function authorizeManga(Request $request){
    	$idUser = $request->input('idUser');
    	$author = User::find($idUser)->author;
    	if ($author == null)
    		return response()->json([
		    'boolean'=>false,
		    'message'=>'User is not an author',
	    ]);
    	$idAuthor = $author->id;
    	$idManga = $request->input('idManga');
    	$checkcount = manga::whereHas('manga_author', function ($query) use ($idAuthor){
    		$query->where('idAuthor','=', $idAuthor);
	    })->where('id','=',$idManga)->count();
    	if ($checkcount >0){
		    return response()->json([
			    'boolean'=>true,
			    'message'=>'This manga belongs to current logged user',
		    ]);
	    }else{
		    return response()->json([
			    'boolean'=>false,
			    'message'=>'The manga is not owned by current logged user',
		    ]);
	    }
    }

    public function indexRecentManga($id){
    	$mangaList = manga::whereHas('manga_author',function ($query) use ($id){
		    $query->where('idAuthor','=',$id);
	    })->orderBy('created_at')->paginate(4);
    	return MangaResource::collection($mangaList);
    }

    public function show($id){
    	$author = author::findOrFail($id);
    	return new MangaResource($author);
    }
}
