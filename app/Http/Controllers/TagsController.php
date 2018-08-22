<?php

namespace App\Http\Controllers;

use App\tags;
use App\Http\Resources\Tags as TagResouce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class TagsController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
	 */
    public function index()
    {
	    //get tags
	    $tags = tags::orderBy('name','asc')->all();
	    return TagResouce::collection($tags);
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
	 * @param  int $id
	 * @return TagResouce
	 */
    public function show($id)
    {
	    $tag = tags::findOrFail($id);
	    return new TagResouce($tag);
    }

    public function searchString(){
	    $text = Input::get('name') ;
    	$tagList = tags::where('name','like','%'.$text.'%')->get();
    	return Response()->json($tagList);
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
