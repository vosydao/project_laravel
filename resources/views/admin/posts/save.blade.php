<?php

$action = ! empty( $action ) ? $action : '';
$post = ! empty( $post ) ? $post : array();
$categories = ! empty( $categories ) ? $categories : array();

$post_title = '';
if ( ! empty( $post->post_title ) ) {
	$post_title = $post->post_title;
}
if ( ! empty( old( 'post_title' ) ) ) {
	$post_title = old( 'post_title' );
}

$post_title = '';
if ( ! empty( $post->post_title ) ) {
	$post_title = $post->post_title;
}
if ( ! empty( old( 'post_title' ) ) ) {
	$post_title = old( 'post_title' );
}

$post_content = '';
if ( ! empty( $post->post_content ) ) {
	$post_content = $post->post_content;
}
if ( ! empty( old( 'post_content' ) ) ) {
	$post_content = old( 'post_content' );
}

$post_excerpt = '';
if ( ! empty( $post->post_excerpt ) ) {
	$post_excerpt = $post->post_excerpt;
}
if ( ! empty( old( 'post_excerpt' ) ) ) {
	$post_excerpt = old( 'post_excerpt' );
}

$post_status = '';
if ( ! empty( $post->post_status ) ) {
	$post_status = $post->post_status;
}
if ( ! empty( old( 'post_status' ) ) ) {
	$post_status = old( 'post_status' );
}


$save_post_status = ! empty( $save_post_status ) ? $save_post_status : '';
$save_post_message = ! empty( $save_post_message ) ? $save_post_message : '';

?>

@extends('layouts.admin')

@section('title')
    @if($action == 'update')
        Edit post
    @else
        Add new post
    @endif
@endsection
@section('admin-bar')
    @if($action == 'update')
        <li><a href="{{ $post->permalink }}">View post</a></li>
    @endif
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            @if($action == 'create')
                Add new post
            @else
                Edit post
            @endif

                <a href="{{ config('app.url') }}/admin/posts">
                    <i class="fa fa-list-ul" aria-hidden="true"></i>
                    All posts</a>
        </div>

        <div class="panel-body">

	        <?php if ($save_post_status == 'success'){ ?>
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Success!</strong> {{ $save_post_message }}
            </div>
	        <?php }
	        if ($save_post_status == 'error'){?>
            <div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Danger!</strong> {{ $save_post_message }}
            </div>
	        <?php } ?>


            <form class="form-save-articles" method="POST" novalidate enctype="multipart/form-data">

                <div class="form-group{{ $errors->has('featured_image') ? ' has-error' : '' }}">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="featured_image" class="control-label">Featured image</label>
                            <input type="file" name="featured_image" id="featured_image"/>
                        </div>
                        <div class="col-md-8">
                            <img id="featured_image_result" src="{{ !empty($post->featured_image_link) ? $post->featured_image_link : '' }}" class="img-rounded" style="max-width: 100%;width: 200px"/>
                        </div>
                    </div>


                    @if ($errors->has('featured_image'))
                        <span class="help-block">
                            <strong>{{ $errors->first('featured_image') }}</strong>
                        </span>
                    @endif
                </div>


                <div class="form-group{{ $errors->has('post_title') ? ' has-error' : '' }}">
                    <label for="post_title" class="control-label">Post title</label>
                    <input id="post_title" type="text" class="form-control" name="post_title"
                           value="{{ $post_title }}" required autofocus>

                    @if ($errors->has('post_title'))
                        <span class="help-block">
                            <strong>{{ $errors->first('post_title') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('post_excerpt') ? ' has-error' : '' }}">
                    <label for="post_excerpt" class="control-label">Post excerpt</label>
                    <textarea id="post_excerpt" rows="5" class="form-control" name="post_excerpt"
                              autofocus>{{ $post_excerpt }}</textarea>
                    @if ($errors->has('post_excerpt'))
                        <span class="help-block">
                            <strong>{{ $errors->first('post_excerpt') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('post_content') ? ' has-error' : '' }}">
                    <label for="post_content" class="control-label">Post content</label>
                    <textarea id="post_content" rows="5" class="form-control" name="post_content"
                              autofocus>{{ $post_content }}</textarea>
                    @if ($errors->has('post_content'))
                        <span class="help-block">
                            <strong>{{ $errors->first('post_content') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group{{ $errors->has('categories') ? ' has-error' : '' }}">
                            <label for="categories" class="control-label">Categories</label>
                            <div class="list-category-scroll">
								<?php
								$list_categories_id = array();
								if ( ! empty( $post->list_categories ) ) {
									foreach ( $post->list_categories as $v ) {
										$list_categories_id[] = $v->id;
									}
								}
								?>
                                @if (! empty( $categories->total() ))
                                    @foreach ($categories as $cate)
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="{{ $cate->id }}" {{ !empty($cate->list_sub->total()) ? 'disabled' : '' }}
                                                       name="categories[]"
                                                        {{ (in_array($cate->id,$list_categories_id)) ? 'checked' : '' }} >
                                                {{ $cate->name }}
                                            </label>
                                        </div>

                                        @if(!empty($cate->list_sub->total()))
                                            @foreach($cate->list_sub as $sub)
                                                <div class="checkbox sub-category">
                                                    <label class="sub">
                                                        <input type="checkbox" value="{{ $sub->id }}"
                                                               name="categories[]"
                                                                {{ (in_array($sub->id,$list_categories_id)) ? 'checked' : '' }} >
                                                        {{ $sub->name }}
                                                    </label>
                                                </div>
                                            @endForeach
                                        @endIf
                                    @endForeach
                                @endIf
                            </div>
                            @if ($errors->has('categories'))
                                <span class="help-block">
                            <strong>{{ $errors->first('categories') }}</strong>
                        </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group{{ $errors->has('post_status') ? ' has-error' : '' }}">
                            <label for="post_status" class="control-label">Post status</label>

                            <div class="list-category-scroll">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="post_status" id="publish"
                                               value="publish" {{ $post_status == "publish" ? 'checked' : '' }}>
                                        Publish
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="post_status" id="pending"
                                               value="pending" {{ $post_status == "pending" ? 'checked' : '' }}>
                                        Pending
                                    </label>
                                </div>
                            </div>

                            @if ($errors->has('post_status'))
                                <span class="help-block">
                            <strong>{{ $errors->first('post_status') }}</strong>
                        </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>
                </div>

                <input type="hidden" name="id" value="{{ !empty($post->id) ?  $post->id : 0 }}">
                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
            </form>
        </div>
    </div>
@endsection


