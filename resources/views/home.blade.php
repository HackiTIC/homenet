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
                        {!!

$chart = Charts::realtime(url('/path/to/json'), 2000, 'gauge', 'google')
            ->values([65, 0, 100])
            ->labels(['First', 'Second', 'Third'])
            ->responsive(false)
            ->height(300)
            ->width(0)
            ->title("Permissions Chart")
            ->valueName('value')->render()
                        !!}
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
