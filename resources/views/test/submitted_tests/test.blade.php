@extends(backpack_view('blank'))

@section('content')
    <h3 class="m-2">@lang('შევსებული ტესტი')</h3>
    <div class="hidden-print with-border m-4">

        <a href="{{ $previousPageLink }}" class="btn btn-primary" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-plus"></i>@lang('წინა გვერდი')</span></a>

    </div>
    <div class="container mt-3">
        <input
        type="checkbox"
         onclick='changeStatus(this)'
         testId="{{ $fullTest->id }}"
         @if ($fullTest->selection_status)
            checked
         @endif
        style="
            margin: 10px;
            ">
            @lang('შერჩეული')
        <ul class="list-group">
            @foreach ($test as $key => $value)
            @php
                $key = implode(" ", explode("_", $key));
                if(!$value) continue;
            @endphp
                    <li class="list-group-item">{{ ucfirst($key) }} : <span style='
                        display: flex;
                        justify-content: center;'>{{ $value }}</span></li>
            @endforeach
            @foreach ($answers as $key => $answer)

                <li class="list-group-item"> {{ $key }} :
                <div style='
                display: flex;
                justify-content: center;'>
                    @if ($answer[0]->option_type == 'file_upload')
                        <a href="{{ $answer[0]->answer }}" target='_blank'>{{ $answer[0]->answer }}</a>
                    @endif
                    @if (count($answer) == 1 && $answer[0]->option_type != 'file_upload')
                        {{ $answer[0]->answer }}
                    @endif

                    @if (count($answer) > 1 && $answer[0]->option_type == 'multi_select')
                        <table style='text-align: center'>
                            <tr>
                                <th>Selected Option</th>
                            </tr>
                        @foreach ($answer as $selected)
                            <tr>
                                <td>{{ $selected->answer }}</td>
                            </tr>
                        @endforeach
                        </table>
                    @endif
                    </div></li>
            @endforeach
        </ul>
        <br />
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
