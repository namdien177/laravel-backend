<?php

namespace App\Http\Controllers;

use App\bookmark;
use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResources;
use App\Http\Resources\Manga as MangaResource;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

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

    public function anonymousToken(){
	    return  response()->json([
		    'anonymous_token' => "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2Fub255bW91cyIsImlhdCI6MTUzNDc4MDUyMywiZXhwIjoxNTM0ODQ1MzIzLCJuYmYiOjE1MzQ3ODA1MjMsImp0aSI6InhFRUR4b2RFMGpiTGp6b0UiLCJzdWIiOiI2Iiwic3JlIjoiQW5vbnltb3VzIn0.cLiyqKt7OgE8vMUuqJrvf8sE-292DkpS_1ZEsSXh8Ic"
	    ]);
    }

//	function generateRandomString($length = 10) {
//		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
//		$charactersLength = strlen($characters);
//		$randomString = '';
//		for ($i = 0; $i < $length; $i++) {
//			$randomString .= $characters[rand(0, $charactersLength - 1)];
//		}
//		return $randomString;
//	}

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
