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
                    <br>
                    <br>
                    @foreach (Route::getRoutes() as $route)
                        {{ print_r($route) }}
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
