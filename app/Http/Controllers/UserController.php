<?php

namespace App\Http\Controllers;

use App\bookmark;
use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResources;
use App\Http\Resources\Manga as MangaResource;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return UserResources
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return new UserResources($user);
    }

    public function showBookmark($id){
    	$bookmarklist = bookmark::whereHas('User', function ($query) use ($id){
		    $query->where('id','=',$id);
	    })->orderBy('read','asc')->get();
    	return MangaResource::collection($bookmarklist);
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
