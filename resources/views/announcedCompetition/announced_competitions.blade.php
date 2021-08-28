@extends(backpack_view('blank'))


@section('content')
<h3 class="m-4">@lang('გამოცხადებული კონკურსები')</h3>

<div class="container mt-3">
        <div class="row">
            @foreach ($competitions as $competition)
            <div class="card" style="width: 30rem; margin: 15px;">
                <div class="card-body">
                    <h5 class="card-title">{{ $competition->title }}</h5>
                    <p class="card-text">@lang('დაწყების თარიღი'): {{$competition->start_date}}</p>
                    <p class="card-text">@lang('დასრულების თარიღი'): {{$competition->end_date}}</p>

                    <a href="{{route('announcedCompetition', $competition->id) }}" class="btn btn-primary">@lang('ტესტის მიბმევა')</a>
                </div>
            </div>
            @endforeach
        </div>
        {{ $competitions->links() }}


</div>
@endsection
