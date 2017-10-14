@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Charts</div>
                <div class="panel-body">
                    @foreach (optional(auth()->user()->house)->rooms ? optional(auth()->user()->house)->rooms : [] as $room)
                        {!! Charts::realtime(route('realtime.temp', $room), 1500, 'line', 'highcharts') ->render() !!}
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
