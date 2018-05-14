@extends('layouts.admin')

@section('content-head')
    <h3>Todo List</h3>
    <p>Things to be done to make our service the best one!</p>
@endsection

@section('content')
    <div class="panel panel-warning">
        <div class="panel-heading">
            <h3 class="panel-title">Todo #{{ $todo['id'] }}</h3>
        </div>

        <div class="panel-body">
            <form class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Todo ID</label>
                    <div class="col-sm-10">
                        <p class="form-control-static">{{ $todo['id'] }}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Created at</label>
                    <div class="col-sm-10">
                        <p class="form-control-static">{{ $todo['created_at'] }}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Created by</label>
                    <div class="col-sm-10">
                        <p class="form-control-static">{!! $linkManager->getUserLink($todo['user_id']) !!}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10">
                        <p class="form-control-static">
                            @if ($todo['status'] === \App\Models\Todo::STATUS_DONE)
                                <label class="label label-success">Done</label>
                            @elseif ($todo['status'] === \App\Models\Todo::STATUS_SKIPPED)
                                <label class="label label-warning">Skipped</label>
                            @elseif ($todo['status'] === \App\Models\Todo::STATUS_NEW)
                                <label class="label label-warning">Todo</label>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Priority</label>
                    <div class="col-sm-10">
                        <p class="form-control-static">
                            @if ($todo['priority'] === \App\Models\Todo::PRIORITY_LOW)
                                <label class="label label-info">Low</label>
                            @elseif ($todo['priority'] === \App\Models\Todo::PRIORITY_MEDIUM)
                                <label class="label label-success">Medium</label>
                            @elseif ($todo['priority'] === \App\Models\Todo::PRIORITY_HIGH)
                                <label class="label label-warning">High</label>
                            @elseif ($todo['priority'] === \App\Models\Todo::PRIORITY_ASAP)
                                <label class="label label-danger">ASAP</label>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10">
                        <p class="form-control-static">
                            @if ($todo['category'] === \App\Models\Todo::CATEGORY_CODE)
                                Coding
                            @elseif ($todo['category'] === \App\Models\Todo::CATEGORY_DESIGN)
                                Design
                            @elseif ($todo['category'] === \App\Models\Todo::CATEGORY_OTHER)
                                Other
                            @endif
                        </p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Title</label>
                    <div class="col-sm-10">
                        <p class="form-control-static">{!! $todo['title'] !!}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10">
                        <p class="form-control-static">{!! nl2br($todo['text']) !!}</p>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if (!empty($comments))
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Comments</h3>
            </div>

            <div class="panel-body">
                <div class="timeline">
                    <!-- Timeline header -->
                    <div class="timeline-header">
                        <div class="timeline-header-title bg-success">Time</div>
                    </div>

                    @foreach ($comments as $comment)
                        <div class="timeline-entry">
                            <div class="timeline-stat">
                                <div class="timeline-icon">
                                    <img src="{{ $comment['user']['steam_image'] }}" alt="Image">
                                </div>
                                <div class="timeline-time">{{ $comment['ago'] }}</div>
                            </div>

                            <div class="timeline-label">
                                <p class="mar-no pad-btm">
                                    {!! $linkManager->getUserLink($comment['user'], 'btn-link') !!} commented on <a href="{{ route('admin.todo.single', ['id' => $todo['id']]) }}" class="text-semibold"><i>Todo #{{ $todo['id'] }}</i></a> <small>at {{ $comment['created_at'] }}</small>
                                </p>
                                <div class="todo-comment">
                                    {!! nl2br($comment['comment']) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Add comment</h3>
        </div>

        <form method="post" action="{{ route('admin.todo.comment', ['id' => $todo['id']]) }}" class="form-horizontal">
            {{ csrf_field() }}
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="todo-comment">Comment</label>
                    <div class="col-sm-9">
                        <textarea name="comment" id="todo-comment" class="form-control" rows="6"></textarea>
                    </div>
                </div>
            </div>
            <div class="panel-footer panel-default text-right">
                <button class="btn btn-primary">Comment</button>
            </div>
        </form>
    </div>
@endsection