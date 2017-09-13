<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 */
	public function __construct()
	{

	}

	public function getList(){
		return view('404');

	}

	public function  getDetail($post_name){
		$objPost = new Post();
		$post = $objPost->getPostByPostName($post_name);

		if(!empty($post)){
			return view('posts.detail',array('post' => $post));
		}else{
			return view('404');
		}
	}
}
