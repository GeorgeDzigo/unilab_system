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
            <div class="card" style="width: 28rem; margin: 15px;">
                <div class="card-body">
                    <h5 class="card-title">{{ $department->name }}</h5>
                    <a href="{{ route('showTestsToAddByDepartment', [$competition->id, $department->id]) }}" class="btn btn-primary">@lang('ტესტის მიბმა')</a>

                    @if ($attachedTests::where('department_id', $department->id)->where('competition_id', $competition->id)->first())
                    @php
                        $attachedTest =  $attachedTests::where('department_id', $department->id)->where('competition_id', $competition->id)->first();
                        $slug = Str::slug($attachedTest->id . '-' . $department->name . '-' . $competition->title );
                    @endphp
                       <button href="{{ route('test.show', $slug) }}" onclick="copyLink(this)" class="btn btn-primary ml-3">@lang('ტესტის ლინკის კოპირება')</button>
                    @endif
                </div>
            </div>
        @endforeach
        </div>
</div>
@endsection


@push('after_scripts')
<script>
    function copyLink(button) {
        var input = document.body.appendChild(document.createElement("input"));
        input.value = button.getAttribute('href');
        input.focus();
        input.select();
        document.execCommand('copy');
        input.parentNode.removeChild(input);

        button.innerText = 'ლინკი დაკოპირდა';
        button.style.backgroundColor = '#28a745';

        setInterval(function () {
            button.innerText = 'ტესტის ლინკის კოპირება';
            button.style.backgroundColor = '#007bff';
        }, 1000);
    }
</script>
@endpush
