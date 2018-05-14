@extends('layouts.admin')

@section('content-head')
    <h3>General settings</h3>
    <p>List of configurable general variables!</p>
@endsection

@section('content')
    <div class="panel panel-warning">
        <div class="panel-heading">
            <h3 class="panel-title">General settings</h3>
        </div>

        {!! $settingsForm->toHtml() !!}
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Rebuild with new css & js prefix</h3>
        </div>

        <form method="post" action="{{ route('admin.general.rebuild') }}">
            {{ csrf_field() }}
            <div class="panel-footer panel-default">
                <button class="btn btn-danger">Rebuild</button>
            </div>
        </form>
    </div>
@endsection