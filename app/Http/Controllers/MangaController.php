<?php

namespace App\Http\Controllers;

use App\author;
use App\bookmark;
use App\Http\Requests;
use App\Http\Resources\MangaTags;
use App\manga_alias;
use App\manga_author;
use App\User as UserDB;
use App\manga;
use App\Http\Resources\Manga as MangaResource;
use App\Http\Resources\MangaTags as MangaTagResource;
use App\manga_chap;
use App\manga_chap_img;
use App\manga_tags;
use App\tags;
use App\User;
use App\viewcount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
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
	    $mangas = Manga::where('authorize','=','1')->orderBy('name','asc')->paginate(10);
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

	function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
	{
		$pieces = [];
		$max = mb_strlen($keyspace, '8bit') - 1;
		for ($i = 0; $i < $length; ++$i) {
			$pieces []= $keyspace[random_int(0, $max)];
		}
		return implode('', $pieces);
	}

	public function addCountView($id, $idChap, $idViewer, Request $request){
		$ipUser = $request->tokenView ."";
		if ($idViewer ==6 ){
			$onserver = viewcount::where('idManga','=',$id)->where('idViewer','=',$idViewer)
				->where('idChap','=',$idChap)->where('IPUser','=',$ipUser)->count();
			if ($onserver > 0 && strlen($ipUser)==40 ){
				return response()->json([
					'boolean'=>false,
					'message'=>'The view was not counted for guess with Token: '.$ipUser,
				]);
			}else if ($onserver ==0  && strlen($ipUser)==40 ){
				$randToken = $this->random_str(40);
				$anonymous = new viewcount;
				$anonymous->idManga = $id;
				$anonymous->idViewer = $idViewer;
				$anonymous->idChap = $idChap;
				$anonymous->IPUser = $ipUser;
				$anonymous->save();
				return response()->json([
					'boolean'=>true,
					'message'=>'The view was counted for guess with Token - '.$ipUser,
					'tokenView'=> $ipUser
				]);
			}else if ($onserver ==0  && strlen($ipUser) == 0 ){
				$randToken = $this->random_str(40);
				$anonymous = new viewcount;
				$anonymous->idManga = $id;
				$anonymous->idViewer = $idViewer;
				$anonymous->idChap = $idChap;
				$anonymous->IPUser = $randToken;
				$anonymous->save();
				return response()->json([
					'boolean'=>true,
					'message'=>'The view was counted for new guess',
					'tokenView'=> $randToken
				]);
			}else{
				return response()->json([
					'boolean'=>false,
					'message'=>'Invalid entry - No token found or token not qualified',
				]);
			}
		}else{
			$view = viewcount::where('idManga','=',$id)->where('idViewer','=',$idViewer)
				->where('idChap','=',$idChap)->count();
			if ($view<=0){
				$viewcount = new viewcount;
				$viewcount->idManga = $id;
				$viewcount->idViewer = $idViewer;
				$viewcount->idChap = $idChap;
				$viewcount->IPUser = $ipUser;
				$viewcount->save();
				return response()->json([
					'boolean'=>true,
					'message'=>'The view was counted for user ID:'.$idViewer." - With IP: ".$ipUser,
					'tokenView'=>$this->random_str(40)
				]);
			}
		}
		return response()->json([
			'boolean'=>false,
			'message'=>'The view was not counted for guess with Token: '.$ipUser,
		]);
	}

	public function getViewCount($id, $idChap){
		$viewCount = viewcount::where('idManga','=',$id)->where('idChap','=',$idChap)->count();
		return $viewCount;
	}

	public function getViewCountAll($id){
		$viewCount = viewcount::where('idManga','=',$id)->count();
		return $viewCount;
	}

	public function getHottestManga($number = 4){
		$listManga = manga::withCount('viewcount')->where('authorize','=','1')
			->orderBy('viewcount_count','desc')->paginate($number);
		return MangaResource::collection($listManga);
	}

	public function getHottestMangaAuthor($number = 4, $id){
		$listManga = manga::whereHas('manga_author', function ($query) use ($id){
			$query->where('idAuthor','=',$id);
		})->withCount('viewcount')->where('authorize','=','1')->orderBy('viewcount_count','desc')->paginate($number);
		return MangaResource::collection($listManga);
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
	    })->where('authorize','=','1')->paginate($number);
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
    	$tagList= $request->input('tags');
    	$aliasnameList=$request->input('aliasName');
    	$authorIDasViewer=$request->input('author');
    	$authorID = author::whereHas('User', function ($query) use ($authorIDasViewer){
		    $query->where('id','=',$authorIDasViewer);
	    })->first()->id;
    	$countOnServer = manga::where('name','like','%'. $request->input('name').'%')->count();
    	if ($countOnServer >0){
    		$manga = new manga;
		    $manga->name = $request->input('name');
		    $manga->cover = $request->input('cover');
		    $manga->description = $request->input('description');
		    $manga->metaURL = $request->input('metaURL');
		    $manga->releaseYear = $request->input('releaseYear');
		    $manga->status = $request->input('status');
    		$manga->authorize = -1;                 //need caution to review as it is duplicated
		    $manga->save();
		    //  to manga_tags table
		    foreach ($tagList as $tag){
			    $newtag = new manga_tags;
			    $newtag->idManga = $manga->id;
			    $newtag->idTag = $tag['id'];
			    $newtag->save();
		    }
		    //  to manga_alias table
		    foreach ($aliasnameList as $name){
			    $alias = new manga_alias;
			    $alias->idManga = $manga->id;
			    $alias->name = $name;
			    $alias->save();
		    }
		    //  to manga_author table
		    $authorLink = new manga_author;
		    $authorLink->idManga = $manga->id;
		    $authorLink->idAuthor = $authorID;
		    $authorLink->save();
		    return Response()->json([
			    'boolean' => true,
			    'message' => 'Your manga is now on the server, but it still won\'t showing up as we have to do some checkup for that. 
			    You know, because your manga is too awesome that we have to make sure it in its awesome-state when showing to everyone!'
		    ]);
	    }else{
		    $manga = new manga;
		    $manga->name = $request->input('name');
		    $manga->cover = $request->input('cover');
		    $manga->description = $request->input('description');
		    $manga->metaURL = $request->input('metaURL');
		    $manga->releaseYear = $request->input('releaseYear');
		    $manga->status = $request->input('status');
		    $manga->authorize = 0;                 //need to authorized
		    $manga->save();
		    //  to manga_tags table
		    foreach ($tagList as $tag){
			    $newtag = new manga_tags;
			    $newtag->idManga = $manga->id;
			    $newtag->idTag = $tag['id'];
			    $newtag->save();
		    }
		    //  to manga_alias table
		    foreach ($aliasnameList as $name){
			    $alias = new manga_alias;
			    $alias->idManga = $manga->id;
			    $alias->name = $name;
			    $alias->save();
		    }
		    //  to manga_author table
		    $authorLink = new manga_author;
		    $authorLink->idManga = $manga->id;
		    $authorLink->idAuthor = $authorID;
		    $authorLink->save();
		    return Response()->json([
			    'boolean' => true,
			    'message' => 'Your manga is now on the server, but it still won\'t showing up now but we will soon approve it 
			     ASAP for the world to read your awesome manga!'
		    ]);
	    }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return MangaResource
     */
    public function show($id)
    {
        $manga = manga::where('authorize','=','1')->where('id','=', $id)->first();
        return new MangaResource($manga);
    }

    public function showName()
    {
    	$searchString = Input::get('name');
    	$searchStringNumber = Input::get('result');
    	if ($searchStringNumber == null){
		    $listmanga = manga::where('name','like','%'.$searchString.'%')->orderBy('name','asc')->paginate(3);
		    return MangaResource::collection($listmanga);
	    }
	    $listmanga = manga::where('name','like','%'.$searchString.'%')->orderBy('name','asc')->paginate($searchStringNumber);
    	return MangaResource::collection($listmanga);
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

	public function getmorelinkChap($id, $chap){
    	$prev = manga_chap::where('idManga', '=', $id)->where('chap', '=', $chap-1)->first();
		$next = manga_chap::where('idManga', '=', $id)->where('chap', '=', $chap+1)->first();
		$linkPrev = null;
		$linkNext = null;
		if ($prev) {
			$linkPrev = 'manga/'.$id.'/chap/'.$prev->chap;
		}
		if ($next){
			$linkNext = 'manga/'.$id.'/chap/'.$next->chap;
		}
		return response()->json([
			'prev'=> $linkPrev,
			'next'=> $linkNext,
		]);
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
