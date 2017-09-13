<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}


	public function getCategories(){
		$objCategory = new Category();
		$list_category = $objCategory->getList();

		$list_category_parent = $objCategory->getList(array(
			'parent' => 0,
		));

		return view('admin.categories.list',array('list' => $list_category, 'list_category_parent' => $list_category_parent));
	}

	public function ajaxSaveCategory(Request $request){
		if($request->ajax()) {
			$objCategory = new Category();

			$save = $objCategory->saveCategory($request);

			if($save){
				$result = array(
					'status' => 'success',
					'message' => 'Save category successful'
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

	public function ajaxDeleteCategory(Request $request){
		if($request->ajax()) {
			$objCategory = new Category();

			$delete = $objCategory->deleteCategory($request['id']);

			if($delete){
				$result = array(
					'status' => 'success',
					'message' => 'Delete category successful'
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

	public function ajaxGetCategory(Request $request){
		if($request->ajax() && !empty($request['id'])) {
			$objCategory = new Category();
			$cate = $objCategory->getDetail($request['id']);

			if(!empty($cate)){
				$result = array(
					'status' => 'success',
					'data' => $cate,
				);
			}else{
				$result = array(
					'status' => 'error',
				);
			}

			echo json_encode($result); exit();
		}

	}

}
