<?php

namespace App\Models;

use App\Helper\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class Post extends Model {

	protected $table = 'posts';

	/**
	 * Get list post by args
	 *
	 * @param array $args
	 *
	 * @return mixed
	 */
	public function getList($args = array()){
		$result = array();

		$query = DB::table($this->table);

		if(!empty($args["post_status"])){
			$status = $args["post_status"];
			$query = $query->whereIn("post_status",$status);
		}else{
			$query = $query->whereIn("post_status",array('publish','pending'));
		}

		$perPage= !empty($args['limit']) ? $args['limit'] : 20;

		$list_post = $query->orderBy('created_at', 'desc')->paginate($perPage);
		/*if(!empty($list_post)){
			foreach ($list_post as &$v){
				$v = $this->getDetail($v->id);
			}
		}*/

		return $list_post;
	}

	/**
	 * Get post detail by id or object
	 *
	 * @param $post
	 *
	 * @return mixed
	 */
	public function getDetail($post){
		if(is_numeric($post)){
			$post = DB::table($this->table)->where('id',$post)->first();
		}

		if(!empty($post)){
			$objCategory = new Category();
			$list_categories = $objCategory->getPostCategory($post->id);
			$post->list_categories = $list_categories;

			$permalink = config('app.url') .'/'.$post->post_name.'.html';
			if(!empty($list_categories)){
				$f_category  = array_shift($list_categories);
				$permalink = config('app.url').'/'. $f_category->slug .'/'.$post->post_name.'.html';
			}
			$post->permalink = $permalink;

			$post->featured_image_link = '';
			if(!empty($post->featured_image)){
				$post->featured_image_link = config('app.url').'/uploads'.'/'.$post->featured_image;
			}
		}

		return $post;
	}

	public function getPostByPostName($post_name){
		$post = DB::table($this->table)->where('post_name',trim($post_name))->first();
		if(!empty($post)){
			$post= $this->getDetail($post);
		}

		return $post;
	}

	/**
	 * Save post
	 *
	 * @param $data
	 *
	 * @return bool
	 */
	public function savePost($data){
		$args_data = array();
		$objHelper = new Helpers();

		if(isset($data['post_title'])){
			$args_data['post_title'] = $data['post_title'];

			$post_name = $objHelper->build_post_name($data['post_title']);
			if(!empty($data['id'])){
				$post_name = $post_name .'-'.$data['id'];
			}
			$args_data['post_name'] = strtolower($post_name);
		}

		if(isset($data['post_excerpt'])){
			$args_data['post_excerpt'] = $data['post_excerpt'];
		}

		if(isset($data['post_content'])){
			$args_data['post_content'] = $data['post_content'];
		}

		if(isset($data['post_status'])){
			$args_data['post_status'] = $data['post_status'];
		}

		if(isset($data['post_author'])){
			$args_data['post_author'] = $data['post_author'];
		}

		if($data->file('featured_image'))
		{
			$image = $data->file('featured_image');

			$filename = time() . '.' . $image->getClientOriginalExtension();
			if(!empty(($args_data['post_name']))){
				$filename = $args_data['post_name'] .'-'.time() . '.' . $image->getClientOriginalExtension();
			}

			$path = public_path('uploads/' . $filename);
			Image::make($image->getRealPath())->resize(900,null, function ($constraint){ $constraint->aspectRatio(); })->crop(900, 456)->save($path);

			$args_data['featured_image'] = $filename;
		}

		if(empty($data['id'])){
			$args_data['created_at'] = date('Y-m-d h:i:s',time());
			$save = $this->insertPost($args_data);
			$id = $save;
		}else{
			$id = $data['id'];
			unset($data['id']);
			$post = DB::table($this->table)->where('id',$id)->first();
			if(!empty($post)){
				$args_data['updated_at'] = date('Y-m-d h:i:s',time());
				if($post->post_status == 'draft'){
					$args_data['created_at'] = date('Y-m-d h:i:s',time());
				}
				$save = $this->updatePost($id,$args_data);
			}else{
				$save = false;
			}
		}

		//save post categories
		if($id && isset($data['categories'])){
			$objCate = new Category();
			$objCate->deletePostCategory($id);
			foreach ($data['categories'] as $cat_id){
				$objCate->insertPostCategory($id,$cat_id);
			}
		}

		if($save){
			return $id;
		}else{
			return false;
		}
	}

	/**
	 * Get post draft for user
	 *
	 * @param $user_id
	 *
	 * @return mixed
	 */
	public function getPostDraft($user_id){
		$post_draft = DB::table($this->table)
		                ->where('post_author', '=', $user_id)
		                ->where('post_status','=','draft')
		                ->first();
		if(empty($post_draft)){
			$args = array(
				'post_author' => $user_id,
				//'created_at' => date('Y-m-d h:i:s',time()),
				'post_status' => 'draft',
			);

			$create = $this->insertPost($args);
			if($create){
				$post_draft = DB::table($this->table)
				                ->where('post_author', '=', $user_id)
				                ->where('post_status','draft')
				                ->first();
			}
		}

		return $post_draft;

	}

	/**
	 * Insert post
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function insertPost($data){
		return DB::table($this->table)->insertGetId($data);
	}

	/**
	 * Update post
	 *
	 * @param $id
	 * @param $data
	 *
	 * @return mixed
	 */
	public function updatePost($id,$data){
		return DB::table($this->table)->where('id', $id)
		                         ->update($data);
	}


	public function deletePost($id){
		if(!empty($id)){
			DB::table($this->table)->where('id', '=', $id)->update(array('post_status' => 'trash'));
			/*$objCategory = new Category();
			$objCategory->deletePostCategory($id);*/

			return true;
		}else{
			return false;
		}
	}
}
