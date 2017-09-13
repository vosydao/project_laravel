<?php
$list_category_parent = ! empty( $list_category_parent ) ? $list_category_parent : array();

?>

@extends('layouts.admin')

@section('title')
    Categories
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Categories
            <a href="javascript:void(0)" id="btn-add-new-category"><i
                        class="fa fa-plus" aria-hidden="true"></i> Add new category</a>
        </div>

        <div class="panel-body">
            <table id="list_member" class="table table-bordered table-striped table-hover">
                <thead>
                <!-- show row title table ( get from widgets row-title-list-account.blade.php ) -->
                <tr>
                    <th>Cate id</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Parent</th>
                    <th>Action</th>
                </tr>

                </thead>

                <tbody>
                @if(!empty($list->total()))
                    @foreach ($list as $key => $cate)
                        <?php $objCategory = new \App\Models\Category();
                        $cate = $objCategory->getDetail($cate->id);
                        ?>
                        <tr id="tr-category-{{ $cate->id }}">
                            <td>{{ $cate->id}}</td>
                            <td><a href="{{ $cate->term_link }}" target="_blank" rel="nofollow"> {{ $cate->name }} </a></td>
                            <td>{{ $cate->description  }}</td>
                            <td>{{ $cate->parent  }}</td>
                            <td>
                                <a href="javascript:void(0)" data-id="{{ $cate->id }}" class="btn-edit-category"
                                   title="Edit">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a> |
                                <a href="javascript:void(0)" data-id="{{ $cate->id }}" class="btn-delete-category"
                                   title="Delete">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">No results</td>
                    </tr>
                @endif
                </tbody>

                <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Parent</th>
                    <th>Action</th>
                </tr>

                </tfoot>
            </table>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="modalSaveCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" id="saveCategory">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="control-label">Category name</label>
                            <input id="name" type="text" class="form-control" name="name"
                                   value="">
                        </div>

                        <div class="form-group">
                            <label for="parent">Parent</label>
                            <select name="parent" id="parent" class="form-control">
                                <option value="0">No parent</option>
                                @if(!empty($list_category_parent))
                                    @foreach ($list_category_parent as $key => $cate)
                                        <option value="{{ $cate->id }}"
                                                id="option-cate-parent-{{ $cate->id }}">{{ $cate->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description" class="control-label">Description</label>
                            <textarea name="description" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>

                    <input type="hidden" value="" name="id">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalDeleteCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delete this category</h4>
                </div>
                <div class="modal-body">
                    <p>Do you want delete this category?</p>
                </div>
                <div class="modal-footer">
                    <form class="form-delete-category">
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <button type="submit" class="btn btn-primary">Delete</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection