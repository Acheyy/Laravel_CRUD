@extends('layouts.base')

@section('scripts')
    <script src="{{ asset('js/home.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
@endsection


@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header"></section>

    <!-- Main content -->
    <section class="content">
            <div class="modal fade" id="category-modal" aria-hidden="true" tabindex="-1" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Category panel</h4>
                            </div>
                            <div class="modal-body">
                                <form id="modalData" class="form-horizontal" enctype="multipart/form-data">
                                    @csrf
                                    @if ($operationState === 'update') @method('PUT') @endif
            
                                    <div class="form-group">
                                        <div class="col-sm-6 control-group">
                                            <label class="control-label" for="category-name">Name</label>
                                            <input type="text" name="title" class="form-control" id="category-name" name="category-name" placeholder="Category name..." value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6 control-group">
                                            <label for="sel1">Subcategory of:</label>
                                                <select class="form-control" id="main-categories-dropdown">
                                                    <option value="0">None</option>
                                                    @foreach ($categories as $category)
                                                        @if(!$category->parent_id)
                                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                                        @endif
                                                    @endforeach

                                                </select>
                                            </div>  
                                        </div>   
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="save-changes" value="add">Save changes</button>
                                <input type="hidden" id="category-id" value="0">
                            </div>
                        </div>
                    </div>
                </div>

        <!-- Default box -->
        <div class="box categories-container">
            <div class="box-header with-border">
                <h3 class="box-title">Options</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                            title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <form>
                    <div class="panel-body">
                            <button id="btn-add" class="btn btn-primary btn-md" data-toggle="modal" data-target="#category-modal">Add new category</button>
                    <button class="btn btn-danger btn-md delete-all-button {{ count($categories) === 0 ? 'disabled' : '' }}">Delete all</button>
                    </div>
                  </form>
            </div>
            <!-- /.box-body -->
            
        </div>
        <!-- /.box -->

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Categories</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                            title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>

            <div class="box-body">
                <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Part of category</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody id="category-list">
                        @foreach ($categories as $category)
                    <tr id="category-item-{{$category->id}}" data-parentId="{{$category->parent_id}}">
                                <td>              
                                    {{$category->id}}
                                </td>
                                <td>
                                    {{$category->name}}
                                </td>
                                <td>
                                    {{$category->parent_id}}
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-info edit toggle-edit-modal" value="{{$category->id}}">Edit</button>                                      
                                        <button class="btn btn-danger delete delete-category" value="{{$category->id}}">Delete</button>
                                    </div>    
                                </td>    
                            </tr>         
                        @endforeach
                    </tbody> 
                  </table>
                  {{ $categories->links() }}  
            </div>
        </div>
    </section>
        <!-- /.content -->
@endsection
