@extends('layouts.admin')

@section('content-head')
    <h3>Users</h3>
    <p>List of all registered user in our service!</p>
@endsection

@section('content')
    <div class="panel panel-warning">
        <div class="panel-heading">
            <h3 class="panel-title">List of users</h3>
        </div>

        @widget('Grid', ['grid' => $userGrid])

        <div class="panel-footer panel-default text-right">
            <ul class="pagination pagination-sm no-margin">
                @if ($currentPage > 1)
                    <li><a href="{{ route('admin.users', ['page' => $currentPage - 1]) }}">Previous page</a></li>
                @endif
                <li><a href="#">{{ $currentPage }}</a></li>
                <li><a href="{{ route('admin.users', ['page' => $currentPage + 1]) }}">Next page</a></li>
            </ul>
        </div>
    </div>
@endsection