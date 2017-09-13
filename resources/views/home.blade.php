<?php
$list_post = !empty($list_post) ? $list_post : array();

?>

@extends('layouts.app')

@section('content')
    <div class="container container-home">
        <div class="row">

                @if(!empty($list_post->total()))
                    @foreach ($list_post as $key => $value)
						<?php $objPost = new \App\Models\Post();
						$post = $objPost->getDetail( $value );
						?>
                            <div class="col-sm-6 col-md-4">
                                <div class="panel panel-default panel-post">
                                    <div class="panel-heading">
                                        <a href="{{ $post->permalink }}" title="{{ $post->post_title }}">
                                            <img src="{{ $post->featured_image_link }}" alt="{{ $post->post_title }}">
                                        </a>
                                        <p class="date-time">{{ date('F d, Y',strtotime($post->created_at)) }}</p>
                                        <h3 class="title">
                                            <a href="{{ $post->permalink }}" title="{{ $post->post_title }}">
                                                {{ $post->post_title }}
                                            </a>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                @endforeach
                @endif
        </div>

    </div>
@endsection
