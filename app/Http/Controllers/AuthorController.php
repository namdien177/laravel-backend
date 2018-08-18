<?php

namespace App\Http\Controllers;

use App\author;
use App\Http\Resources\Manga as MangaResource;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(){
	    $author = author::orderBy('name','asc')->paginate(24);
	    return MangaResource::collection($author);
    }
}
