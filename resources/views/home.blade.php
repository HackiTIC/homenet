@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ print_r(auth()->user()->load('house')->toArray()) }}
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Users</div>

                <div class="panel-body">
                    @foreach (\App\User::all() as $user)
                        {{ print_r($user) }}
                    @endforeach
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Homes</div>

                <div class="panel-body">
                    @foreach (\App\Home::all() as $home)
                        {{ print_r($home) }}
                    @endforeach
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Users</div>

                <div class="panel-body">
                    @foreach (\App\Room::all() as $room)
                        {{ print_r($room) }}
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
