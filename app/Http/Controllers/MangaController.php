<?php

namespace App\Http\Controllers;

use App\author;
use App\bookmark;
use App\Http\Requests;
use App\Http\Resources\MangaTags;
use App\Http\Resources\User;
use App\User as UserDB;
use App\manga;
use App\Http\Resources\Manga as MangaResource;
use App\Http\Resources\MangaTags as MangaTagResource;
use App\manga_author;
use App\manga_chap;
use App\manga_chap_img;
use App\manga_tags;
use App\tags;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Contracts\Providers\Auth;

class MangaController extends Controller
{
    /**
     * Display a listing of the resource.
     *  get all manga (by paginate 10 each)
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function index()
    {
	    $mangas = Manga::orderBy('name','asc')->paginate(10);
	    return MangaResource::collection($mangas);
    }

	/**
	 * get all chap of an manga (with known ID)
	 * @param $id
	 * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
	 */
    public function indexChap($id){
    	$chap = manga_chap::whereHas('manga',function ($query) use ($id){
		    $query->where('id','=',$id);
	    })->orderBy('chap','asc')->paginate(75);
    	return MangaResource::collection($chap);
    }

	/**
	 * get author of the manga
	 * @param $id
	 * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
	 */
	public function indexAuthor($id){
    	$author = author::whereHas('manga_author', function ($query) use ($id){
		    $query->where('idManga','=',$id);
	    })->orderBy('name','asc')->get();
    	return MangaResource::collection($author);
	}

	/**
	 * add new manga to bookmark list
	 * @param $id
	 * @param $idUser
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function bookmarkManga($id, $idUser){
		$user = UserDB::where('id', $idUser)->count() > 0;
		if ($user){
			$alreadyExisted = bookmark::where('idUser',"=" , $idUser)->where('idManga',"=" , $id)->count() > 0;
			if ($alreadyExisted) return response()->json([
				'boolean'=>false,
				'message'=>'This manga was already bookmarked',
			]);
			else{
				$bookmark = new bookmark;
				$bookmark->idUser = $idUser;
				$bookmark->idManga = $id;
				if ($bookmark->save()){
					return response()->json([
						'boolean'=>true,
						'message'=>'The manga is bookmarked successfully',
					]);
				}
			}
		}
		return response()->json([
			'boolean'=>false,
			'message'=>'There is something wrong when bookmarking this manga',
		]);
	}

	/**
	 * Un-bookmarking a manga from a user
	 * @param $id
	 * @param $idUser
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function unbookmarkManga($id, $idUser){
		$user = UserDB::where('id','=', $idUser)->count() > 0;
		if ($user){
			$alreadyExisted = bookmark::where('idUser',"=" , $idUser)->where('idManga',"=" , $id)->count() > 0;
			if (!$alreadyExisted) return response()->json([
				'boolean'=>false,
				'message'=>'This manga was not being bookmarked before',
			]);
			else{
				if (bookmark::where('idUser', '=', $idUser)->where('idManga','=',$id)->delete()){
					return response()->json([
						'boolean'=>true,
						'message'=>'The manga is now un-bookmarked',
					]);
				}
			}
		}
		return response()->json([
			'boolean'=>false,
			'message'=>'There is something wrong when un-bookmarking this manga',
		]);
	}

	/**
	 * Mark a manga in bookmark as read
	 * @param $id
	 * @param $idUser
	 * @return string
	 */
	public function markRead($id, $idUser){
		$user = UserDB::where('id', $idUser)->count() > 0;
		if ($user){
			$alreadyExisted = bookmark::where('idUser',"=" , $idUser)->where('idManga',"=" , $id)->count() > 0;
			if (!$alreadyExisted) return response()->json([
				'boolean'=>false,
				'message'=>'You haven\'t bookmarked this manga',
			]);
			else{
				$manga = bookmark::where('idUser', $idUser)->where('idManga',$id)->first();
				$manga->read = 2;
				if ($manga->save()){
					return response()->json([
						'boolean'=>true,
						'message'=>'The manga is marked as read',
					]);
				}
			}
		}
		return response()->json([
			'boolean'=>false,
			'message'=>'There was something wrong when marking as read this manga',
		]);
	}

	/**
	 * Mark a manga in bookmark as unread
	 * @param $id
	 * @param $idUser
	 * @return string
	 */
	public function markunRead($id, $idUser){
		$user = UserDB::where('id', $idUser)->count() > 0;
		if ($user){
			$alreadyExisted = bookmark::where('idUser',"=" , $idUser)->where('idManga',"=" , $id)->count() > 0;
			if (!$alreadyExisted) return response()->json([
				'boolean'=>false,
				'message'=>'You haven\'t bookmarked this manga',
			]);
			else{
				$manga = bookmark::where('idUser', $idUser)->where('idManga',$id)->first();
				$manga->read = 1;
				if ($manga->save()){
					return response()->json([
						'boolean'=>true,
						'message'=>'The manga is unread now',
					]);
				}
			}
		}
		return response()->json([
			'boolean'=>false,
			'message'=>'There is something wrong while marking as unread this manga',
		]);
	}

	/**
	 * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
	 *
	 */
	public function getUpdateManga($number){
    	$listManga = manga::whereHas('manga_chap', function ($query) {
    		$query->orderBy('updated_at','asc');
	    })->paginate($number);
    	return MangaResource::collection($listManga);
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return MangaResource
     */
    public function show($id)
    {
        $manga = manga::findOrFail($id);
        return new MangaResource($manga);
    }

	public function showTags($id)
	{
		$tags = tags::whereHas('manga_tags',function ($query) use ($id){
			$query->where('idManga','=',$id);
		})->get();
		return MangaTagResource::collection($tags);
	}

	public function showChap($id, $idChap){
		$img = manga_chap_img::whereHas('manga_chap',function ($query) use ($id){
			$query->where('idManga','=',$id);
		})->where('idChap','=',$idChap)->orderBy('id','asc')->get();
		return MangaTags::collection($img);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
