@extends(backpack_view('blank'))


@section('content')
<h3 class="m-4">@lang('გამოცხადებული კონკურსის დეპარტამენტები')</h3>

<div class="container mt-3">
        <div class="row">
        @foreach ($departments as $department)
        @php
            if(!$department) continue;
            $department = $department->department;

        @endphp
            <div class="card" style="width: 25rem; margin: 15px;">
                <div class="card-body">
                    <h5 class="card-title">{{ $department->name }}</h5>
                    <a href="{{route('submittedTests.tests', [$competition_id, $department->id])}}" class="btn btn-primary">@lang('შევსებული ტესტები')</a>
                    <a href="{{ route('tests.export.department', [$department->id, $competition_id]) }}" class="btn btn-primary ml-3">@lang('ექსპორტი')</a>
                </div>
            </div>
        @endforeach
        </div>
</div>
@endsection
