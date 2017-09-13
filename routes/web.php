<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/','HomeController@index');


/**
 * Author url
 */
Auth::routes();

///**
// * Admin url
// */
Route::prefix('admin')->group(function () {
	Route::get('/','Admin\DashboardController@index');
	Route::get('/dashboard','Admin\DashboardController@index');

	Route::get('/setting','Admin\SettingController@index');

	Route::get('/posts','Admin\PostsController@index');
	Route::get('/posts/edit','Admin\PostsController@getUpdate');
	Route::post('/posts/edit','Admin\PostsController@postUpdate');
	Route::get('/posts/add-new','Admin\PostsController@getCreate');
	Route::post('/posts/add-new','Admin\PostsController@postCreate');
	Route::post('/ajax-delete-post','Admin\PostsController@ajaxDeletePost');
	Route::get('/categories','Admin\CategoriesController@getCategories');
	Route::post('/ajax-save-category','Admin\CategoriesController@ajaxSaveCategory');
	Route::post('/ajax-delete-category','Admin\CategoriesController@ajaxDeleteCategory');
	Route::get('/ajax-get-category','Admin\CategoriesController@ajaxGetCategory');

	Route::get('/users', 'Admin\AccountController@index');
});

//\Zizaco\Entrust\Entrust::routeNeedsPermission();

// post url
Route::get('{category}/{post_name}.html', function ( $category,$post_name) {
	$app = new \App\Http\Controllers\PostsController();
	return $app->getDetail($post_name);
});

//page and category parent url
Route::get('{slug}', function ($slug) {
	return $slug;
});

//page category child url
Route::get('{parent}/{child}', function ($parent,$child) {
	return $parent.'-'.$child;
});

// Cache
Route::get('/clear-cache', function()
{
	Artisan::call('cache:clear');
});