<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Resources\MangaTags;
use App\manga;
use App\Http\Resources\Manga as MangaResource;
use App\Http\Resources\MangaTags as MangaTagResource;
use App\manga_chap;
use App\manga_chap_img;
use App\manga_tags;
use App\tags;
use Illuminate\Http\Request;

class MangaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function index()
    {
        //get manga
	    $mangas = Manga::orderBy('name','asc')->paginate(10);
	    return MangaResource::collection($mangas);
    }

    public function indexChap($id){
    	$chap = manga_chap::whereHas('manga',function ($query) use ($id){
		    $query->where('id','=',$id);
	    })->orderBy('chap','asc')->paginate(75);
    	return MangaTags::collection($chap);
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
