<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class PostsController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$modelPosts = new Post();
		$list_post =  $modelPosts->getList();

		$save_post_status =$request->session()->get('save_post_status');
		$save_post_message =$request->session()->get('save_post_message');

		return view('admin.posts.list',array('list'=> $list_post,
		                                     'save_post_status' => $save_post_status,
		                                     'save_post_message' => $save_post_message,
			));
	}


	public function getCreate(Request $request){
		$objPost = new Post();
		$objAuthor = new Auth();

		$save_post_status =$request->session()->get('save_post_status');
		$save_post_message =$request->session()->get('save_post_message');

		$user_id = $objAuthor::id();
		$post_draft = $objPost->getPostDraft($user_id);

		$objCategory = new Category();
		$categories = $objCategory->getList(array('parent' => 0));
		if(!empty($categories)){
			foreach ($categories as &$category){
				$list_sub_category = $objCategory->getList(array('parent' => $category->id));
				$category->list_sub = !empty($list_sub_category) ? $list_sub_category : array();
			}
		}

		return view('admin.posts.save',['action' => 'create','post' => $post_draft,
		                                'categories' => $categories,
		                                'save_post_status' => $save_post_status,
		                                'save_post_message' => $save_post_message,
		]);
	}

	public function postCreate(Request $request){
		$this->validate($request, [
			'post_title' => 'required',
			'post_excerpt' => 'required',
			'post_content' => 'required',
			'post_status' => 'required',
			'categories' => 'required',
			//'featured_image' => 'required',
		]);

		$modelPosts = new Post();
		$insert = $modelPosts->savePost($request);

		if($insert){
			$request->session()->flash('save_post_status', 'success');
			$request->session()->flash('save_post_message', 'Add new post successful');

			return redirect(config('site.url').'/admin/posts/edit?id='.$insert);
		}else{
			$request->session()->flash('save_post_status', 'error');
			$request->session()->flash('save_post_message', 'An error, please try again.');

			return redirect(config('site.url').'/admin/posts/add-new');
		}
	}

	public function getUpdate(Request $request){
		$objPost = new Post();
		$post_id = $_GET['id'];
		$post = $objPost->getDetail($post_id);

		$save_post_status =$request->session()->get('save_post_status');
		$save_post_message =$request->session()->get('save_post_message');

		if(!empty($post)){
			$objCategory = new Category();
			$categories = $objCategory->getList(array('parent' => 0));
			if(!empty($categories)){
				foreach ($categories as &$category){
					$list_sub_category = $objCategory->getList(array('parent' => $category->id));
					$category->list_sub = !empty($list_sub_category) ? $list_sub_category : array();
				}
			}

			return view('admin.posts.save',['action' => 'update','post' => $post,
			                                'categories' => $categories,
			                                'save_post_status' => $save_post_status,
			                                'save_post_message' => $save_post_message,
			]);
		}else{
			return redirect(config('site.url').'/admin/posts');
		}
	}

	public function postUpdate(Request $request){
		$this->validate($request, [
			'post_title' => 'required',
			'post_excerpt' => 'required',
			'post_content' => 'required',
			'post_status' => 'required',
			'categories' => 'required',
			//'featured_image' => 'required',
		]);

		$modelPosts = new Post();
		$save = $modelPosts->savePost($request);
		if($save){
			$request->session()->flash('save_post_status', 'success');
			$request->session()->flash('save_post_message', 'Update post successful');
		}else{
			$request->session()->flash('save_post_status', 'error');
			$request->session()->flash('save_post_message', 'An error, please try again.');
		}

		return redirect(config('site.url').'/admin/posts/edit?id='.$request['id']);
	}

	public function ajaxDeletePost(Request $request){
		if($request->ajax()) {
			$objPost = new Post();

			$delete = $objPost->deletePost($request['id']);

			if($delete){
				$result = array(
					'status' => 'success',
					'message' => 'Delete post successful'
				);
			}else{
				$result = array(
					'status' => 'error',
					'message' => 'An error, please try again.'
				);
			}

			echo json_encode($result); exit();
		}

	}

	public function ajaxUploadFeaturedImage(Request $request){

	}

}
