@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Charts</div>
                <div class="panel-body">
                    @php
                        $charts = [];
                    @endphp
                    @foreach (optional(auth()->user()->house)->rooms ? optional(auth()->user()->house)->rooms : [] as $room)
                        {!!
                            $chart = Charts::create('temp', 'canvas-gauges')
                                ->values([0, 0, 100])
                                ->render()
                        !!}
                        @php
                            array_push($charts, $chart);
                        @endphp
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
