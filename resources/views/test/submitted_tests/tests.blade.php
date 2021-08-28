@extends(backpack_view('blank'))


@section('content')
    <h3 class="m-4">@lang('შევსებული ტესტები')</h3>

    <div class="container mt-3">
        <div class="row">
            @foreach ($submittedTests as $submittedTest)
                <div class="card" style="width: 20rem; margin: 15px;">
                    <input
                    type="checkbox"
                     onclick='changeStatus(this)'
                     testId="{{ $submittedTest->id }}"
                     @if ($submittedTest->selection_status)
                        checked
                     @endif
                    style="margin-left: 90%;
                        top: 10%;
                        position: relative;
                        ">
                    <div class="card-body">
                        <p>@lang('სახელი') : {{ $submittedTest->first_name }} {{ $submittedTest->last_name }}</p>
                        <p>@lang('შევსების დრო'): {{ $submittedTest->created_at }}</p>
                        <a href="{{ route('submittedTests.test', [$competition_id, $department_id, $submittedTest->id]) }}"
                            class="btn btn-primary">@lang('ნახვა')</a>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $submittedTests->links() }}
    </div>
@endsection


@push('after_scripts')
    <script>
        function changeStatus(checkbox) {
            $.ajax({
                url: '/api/change/status/' + checkbox.getAttribute('testid'),
                type: 'post',
                data: {
                    status: checkbox.checked,
                },
            });
        }
    </script>
@endpush
