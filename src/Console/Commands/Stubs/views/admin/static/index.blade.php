@extends('layouts.admin')

@section('content-head')
    <h3>Static pages</h3>
    <p>Manage static pages of our service!</p>
@endsection

@section('content')
    <div class="panel panel-warning">
        <div class="panel-heading">
            <h3 class="panel-title">Static pages</h3>
        </div>

        @widget('Grid', ['grid' => $staticPageGrid])

        <div class="panel-footer panel-default text-right">
            <a href="{{ route('admin.static.add') }}" class="btn btn-warning">Add new static page</a>
        </div>
    </div>
@endsection