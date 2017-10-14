@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Auth Informatiion</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <ul>
                        @foreach (auth()->user()->load('house')->toArray() as $key => $value)
                            <li>
                                <b>{{ $key }}:</b> {{ is_array($value) ? print_r($value) : $value }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Users</div>

                <div class="panel-body">
                    @foreach (\App\User::all() as $user)
                        <ul>
                            @foreach ($user->toArray() as $key => $value)
                                <li><b>{{ $key }}:</b> {{ $value }}</li>
                            @endforeach
                        </ul>
                    @endforeach
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Homes</div>

                <div class="panel-body">
                    @foreach (\App\House::all() as $home)
                        <ul>
                            @foreach ($home->toArray() as $key => $value)
                                <li><b>{{ $key }}:</b> {{ $value }}</li>
                            @endforeach
                        </ul>
                    @endforeach
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Rooms</div>

                <div class="panel-body">
                    @foreach (\App\Room::all() as $room)
                        <ul>
                            @foreach ($room->toArray() as $key => $value)
                                <li><b>{{ $key }}:</b> {{ $value }}</li>
                            @endforeach
                        </ul>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
