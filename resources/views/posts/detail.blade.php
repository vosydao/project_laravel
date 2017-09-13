<?php
$post = !empty($post) ? $post : array();

?>

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>{{$post->post_title}}</h1>
            </div>

            <div class="panel-body">
                <h2>{{ $post->post_excerpt }}</h2>

			    <?php echo $post->post_content ?>
            </div>
        </div>
    </div>
@endsection
