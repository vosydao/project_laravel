<?php

namespace App\Models;

use App\Helper\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model {

	protected $table = 'categories';
	protected $table_post_category = 'post_category';

	/**
	 * Get list category by args
	 *
	 * @param array $args
	 *
	 * @return mixed
	 */
	public function getList($args = array()){
		$result = array();

		$query = DB::table($this->table);
		if(isset($args['parent'])) {
			$query = $query->where( "parent", '=', $args['parent'] );
		}

		$perPage= !empty($args['limit']) ? $args['limit'] : 20;

		$list_category = $query->orderBy('id', 'desc')->paginate($perPage);
		if(!empty($list_category)){
			foreach ($list_category as &$v){
				$v = $this->getDetail($v->id);
			}
		}

		return $list_category;
	}

	/**
	 * Get category detail by id or object
	 *
	 * @param $category
	 *
	 * @return mixed
	 */
	public function getDetail($category){

		if(is_numeric($category)){
			$category = DB::table($this->table)->where('id',$category)->first();
		}

		if(!empty($category)){
			$term_link = config('app.url').'/'.$category->slug;
			if(!empty($category->parent)){
				$parent =  DB::table($this->table)->where('id',$category->parent)->first();
				if(!empty($parent)){
					$term_link = config('app.url').'/'.$parent->slug.'/'.$category->slug;
				}
			}
			$category->term_link = $term_link;
		}

		return $category;
	}

	/**
	 * Save category
	 *
	 * @param $data
	 *
	 * @return bool
	 */
	public function saveCategory($data){
		$args_data = array();
		$objHelper = new Helpers();

		if(!empty($data['id'])){
			$id = $data['id'];
			unset($data['id']);
			$category_current = DB::table($this->table)->where('id',$id)->first();
		}

		if(isset($data['name'])){
			$args_data['name'] = trim($data['name']);

			if(empty($category_current) or $category_current->name != $args_data['name']){
				$post_name = $objHelper->build_post_name($args_data['name']);
				if(!empty($post_name)) $post_name = strtolower($post_name);
				$args_data['slug'] = $this->checkSlug($post_name);
			}
		}

		if(isset($data['description'])){
			$args_data['description'] = $data['description'];
		}

		if(isset($data['parent'])){
			$args_data['parent'] = $data['parent'];
		}

		$args_data['updated_at'] = date('Y-m-d h:i:s',time());
		if(empty($id)){
			$save = DB::table($this->table)->insertGetId($args_data);
		} else {
			if(!empty($category_current)){
				$save = DB::table($this->table)->where('id', $id)
				          ->update($args_data);
			}else{
				$save = false;
			}
		}

		return $save;
	}

	/**
	 * Delete category
	 *
	 * @param $id
	 *
	 * @return bool
	 */
	public function deleteCategory($id){
		if(!empty($id)){
			DB::table($this->table_post_category)->where('category_id', '=', $id)->delete();
			DB::table($this->table)->where('id', '=', $id)->delete();

			return true;
		}else{
			return false;
		}
	}

	/**
	 * Check category slug
	 *
	 * @param $slug
	 *
	 * @return string
	 */
	public function checkSlug($slug){
		$slug = trim($slug);
		$category =  DB::table($this->table)->where('slug',$slug)->first();
		if(empty($category)){
			return $slug;
		}else{
			$i = 1;
			do {
				$i++;
				$slug_new = $slug.'-'.$i;
				$category_new =  DB::table($this->table)->where('slug',$slug_new)->first();
			} while (!empty($category_new));

			return $slug_new;
		}
	}

	/**
	 * get Post category
	 *
	 * @param $post_id
	 *
	 * @return array
	 */
	public function getPostCategory($post_id){
		$result = array();
		$list =  DB::table($this->table_post_category)->where('post_id', '=', $post_id)->get();
		if(!empty($list)){
			foreach ($list as $v){
				$category_id = $v->category_id;
				$category = $this->getDetail($category_id);
				$result[] = $category;
			}
		}

		return $result;

	}

	/**
	 * Delete post category
	 *
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function deletePostCategory($post_id){
		return DB::table($this->table_post_category)->where('post_id', '=', $post_id)->delete();
	}

	/**
	 * Insert post category
	 *
	 * @param $post_id
	 * @param $cate_id
	 *
	 * @return mixed
	 */
	public function  insertPostCategory($post_id,$cate_id){
		$args = array(
			'post_id' => $post_id,
			'category_id' => $cate_id,
		);

		return DB::table($this->table_post_category)->insert($args);
	}
}
