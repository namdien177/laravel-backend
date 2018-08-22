<?php

namespace App\Http\Controllers;

use App\author;
use App\Http\Resources\Manga as MangaResource;
use App\manga;
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

    public function indexRecentManga($id){
    	$mangaList = manga::whereHas('manga_author',function ($query) use ($id){
		    $query->where('idAuthor','=',$id);
	    })->orderBy('created_at')->paginate(4);
//	    $mangaList = manga::whereHas('manga_author',function ($query) use ($id){
//		    $query->where('idAuthor','=',$id);
//	   })->get();
//	    $listorder = $mangaList->manga_chaps->sortBy('created_at');
    	return MangaResource::collection($mangaList);
    }

    public function show($id){
    	$author = author::findOrFail($id);
    	return new MangaResource($author);
    }
}
