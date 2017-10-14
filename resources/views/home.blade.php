@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Charts</div>
                <div class="panel-body">
                    @foreach (auth()->user()->house->rooms as $room)
                        Chart :)
                        @php
                            $chart = Charts::realtime(route('realtime.temp', $room), 1500, 'gauge', 'google')
                        @endphp
                        {!! $chart->html() !!}
                        {!! $chart->script() !!}
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
