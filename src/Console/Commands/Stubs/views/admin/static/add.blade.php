@extends('layouts.admin')

@section('content-head')
    <h3>Static pages</h3>
    <p>Add static page to our service!</p>
@endsection

@section('content')
    <div class="panel panel-warning">
        <div class="panel-heading">
            <h3 class="panel-title">New static page</h3>
        </div>

        <form method="post" action="{{ route('admin.static.create') }}" class="form-horizontal">
            {{ csrf_field() }}
            <div class="panel-body">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger">{{ $error }}</div>
                    @endforeach
                @endif

                <div class="form-group">
                    <label class="col-sm-3 control-label" for="static-slug">Slug</label>
                    <div class="col-sm-9">
                        <input type="text" name="slug" class="form-control" id="static-slug" value="{{ old('slug') }}">
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-3 control-label" for="static-title">Title</label>
                    <div class="col-sm-9">
                        <input type="text" name="title" class="form-control" id="static-title" value="{{ old('title') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label" for="static-content">Content</label>
                    <div class="col-sm-9">
                        <textarea name="content" id="static-content" class="form-control">{{ old('content') }}</textarea>
                    </div>
                </div>

                <script>
                    waitFor(['jQuery', 'CKEDITOR'], function () {
                        jQuery(document).ready(function () {
                            CKEDITOR.replace('static-content');
                        });
                    });
                </script>
            </div>

            <div class="panel-footer panel-default text-right">
                <button class="btn btn-warning">Create</button>
            </div>
        </form>
    </div>
@endsection