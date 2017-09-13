<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class HomeController extends Controller
{

	public function __construct()
	{

	}
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
	public function index(Request $request)
	{
		$modelPost = new Post();

		$list_post =$modelPost->getList();


		return view('home',compact('list_post'));
	}
}
