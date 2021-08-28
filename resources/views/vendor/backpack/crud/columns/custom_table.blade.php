@php

   $values =  $entry->{$column['function_name']}

@endphp

<span>

    @if ($values->count() != 0)
        <table>
            <tr>
                <th>Title</th>
                <th>Correct</th>
            </tr>
        @foreach ($values as $value)
            <tr>
                <td>{{ $value->option_select_title }}</td>
                <td>{{ $value->option_is_correct == 0 ? 'false' : 'true' }}</td>
            </tr>
        @endforeach
        </table>
    @else
        -
    @endif
</span>
