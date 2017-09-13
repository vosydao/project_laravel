<?php $list = ! empty( $list ) ? $list : array();
$save_post_status = ! empty( $save_post_status ) ? $save_post_status : '';
$save_post_message = ! empty( $save_post_message ) ? $save_post_message : '';

?>

@extends('layouts.admin')
@section('title')
    List posts
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">List posts
            <a href="{{ config('app.url') }}/admin/posts/add-new"><i
                        class="fa fa-plus" aria-hidden="true"></i> Add new post</a>
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

            <table id="list_member" class="table table-bordered table-striped table-hover">
                <thead>
                <!-- show row title table ( get from widgets row-title-list-account.blade.php ) -->
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Categories</th>
                    <th>Status</th>
                    <th>Created at</th>
                    <th>Action</th>
                </tr>

                </thead>

                <tbody>
                @if(!empty($list->total()))
                    @foreach ($list as $key => $value)
						<?php $objPost = new \App\Models\Post();
						$post = $objPost->getDetail( $value );
						?>
                        <tr id="tr-post-{{ $post->id }}">
                            <td>{{ $post->id}}</td>
                            <td><a href="{{ $post->permalink }}" target="_blank"
                                   title="{{ $post->post_title }}">{{ $post->post_title }}</a></td>
                            <td>
                                @if(!empty($post->list_categories))
                                    @foreach($post->list_categories as $cate)
                                        <span class="bg-info">{{ $cate->name }}</span>
                                    @endForeach
                                @endIf
                            </td>
                            <td>
                                @if($post->post_status =='publish')
                                    <span class="bg-primary">{{ $post->post_status  }}</span>
                                @else
                                    <span class="bg-danger">{{ $post->post_status  }}</span>
                                @endif
                            </td>
                            <td style="white-space: nowrap">{{ date('H:i, d/m/Y',strtotime($post->created_at) )  }}</td>
                            <td style="white-space: nowrap">
                                <a href="{{ config('app.url').'/admin/posts/edit?id='.$post->id }}" title="Edit">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>
                                |
                                <a href="javascript:void(0)" data-id="{{ $post->id }}" class="btn-delete-post" title="Trash">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">No results</td>
                    </tr>
                @endif
                </tbody>

                <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Categories</th>
                    <th>Status</th>
                    <th>Created at</th>
                    <th>Action</th>
                </tr>

                </tfoot>
            </table>
            <div class="row" style="margin-top: 10px">
                <div class="col-xs-4">
                    <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">
						<?php isset( $_GET['page'] ) ? $page = $_GET['page'] : $page = 1; ?>
                        Showing {{ ($page == 1) ? 1 : ($page-1)*$list->perPage() }}
                        to {{ $page*$list->perPage() }} of <b>{!! $list->total() !!}</b> entries
                    </div>
                </div>
                <div class="col-xs-8">
                    <div class="dataTables_paginate paging_simple_numbers cnt_paginate">
                        {!! $list->appends(['daterange_created' => isset($_GET['daterange_created'])?$_GET['daterange_created']:"","keyword" => isset($_GET['keyword'])?$_GET['keyword']:"","status" => isset($_GET['status'])?$_GET['status']:"" ])->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalDeletePost" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Trash this post</h4>
                </div>
                <div class="modal-body">
                    <p>Do you want trash this post?</p>
                </div>
                <div class="modal-footer">
                    <form class="form-delete-post">
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <button type="submit" class="btn btn-primary">Trash</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
