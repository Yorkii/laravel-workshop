@extends('layouts.admin')

@section('content-head')
    <h3>Todo List</h3>
    <p>Things to be done to make our service the best one!</p>
@endsection

@section('content')
    <div class="panel panel-warning">
        <div class="panel-heading">
            <div class="panel-control">
                <span class="badge badge-danger">{{ $todoCount }}</span>
            </div>
            <h3 class="panel-title">List of things to do</h3>
        </div>

        @widget('Grid', ['grid' => $todoGrid])
    </div>

    <div class="panel panel-success">
        <div class="panel-heading">
            <div class="panel-control">
                <span class="badge badge-default">{{ $todoCountDone }}</span>
            </div>
            <h3 class="panel-title">List of things done</h3>
        </div>

        @widget('Grid', ['grid' => $doneGrid])
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Add new todo</h3>
        </div>

        <form method="post" action="{{ route('admin.todo.create') }}" class="form-horizontal">
            {{ csrf_field() }}
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="todo-priority">Priority</label>
                    <div class="col-sm-9">
                        <select name="priority" id="todo-priority" class="form-control">
                            <option value="{{ \App\Models\Todo::PRIORITY_LOW }}">Low</option>
                            <option value="{{ \App\Models\Todo::PRIORITY_MEDIUM }}">Medium</option>
                            <option value="{{ \App\Models\Todo::PRIORITY_HIGH }}">High</option>
                            <option value="{{ \App\Models\Todo::PRIORITY_ASAP }}">ASAP</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label" for="todo-category">Category</label>
                    <div class="col-sm-9">
                        <select name="category" id="todo-category" class="form-control">
                            <option value="{{ \App\Models\Todo::CATEGORY_CODE }}">Coding</option>
                            <option value="{{ \App\Models\Todo::CATEGORY_DESIGN }}">Design</option>
                            <option value="{{ \App\Models\Todo::CATEGORY_OTHER }}">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label" for="todo-title">Title</label>
                    <div class="col-sm-9">
                        <input name="title" id="todo-title" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label" for="todo-description">Description (optional)</label>
                    <div class="col-sm-9">
                        <textarea name="text" id="todo-description" class="form-control" rows="12"></textarea>
                    </div>
                </div>
            </div>
            <div class="panel-footer panel-default text-right">
                <button class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
@endsection