@extends('layouts.admin')

@section('content-head')
    <h3>User</h3>
    <p>Details of user registered in our service!</p>
@endsection

@section('content')
    <div class="panel panel-warning">
        <div class="panel-heading">
            <h3 class="panel-title">User #{{ $user['id'] }} details</h3>
        </div>

        <div class="panel-body">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label">User ID</label>
                    <div class="col-sm-10">
                        <p class="form-control-static">{{ $user['id'] }}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10">
                        <p class="form-control-static">{{ $user['name'] }}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <p class="form-control-static">{{ $user['email'] }}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Registered at</label>
                    <div class="col-sm-10">
                        <p class="form-control-static">{{ $user['created_at'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection