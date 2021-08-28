<table>
    <thead>
    <tr>
        <th>@lang('დეპარტამენტი')</th>
        <th>@lang('ტესტი')</th>
        <th>@lang('კონკურსი')</th>
        <th>@lang('სახელი')</th>
        <th>@lang('გვარი')</th>
        <th>@lang('ტელეფონის ნომერი')</th>
        <th>@lang('მეილი')</th>
        <th>@lang('მისამართი')</th>
        <th>@lang('სამუშაო გამოცდილება')</th>
        <th>@lang('დაბადების თარიღი')</th>
        <th>@lang('პირადი ნომერი')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($submittedTests as $submittedTest)
        <tr>
            <td>{{ $submittedTest->department->name }}</td>
            <td>{{ $submittedTest->test->title }}</td>
            <td>{{ $submittedTest->competition->title }}</td>
            <td>{{ $submittedTest->first_name }}</td>
            <td>{{ $submittedTest->last_name }}</td>
            <td>{{ $submittedTest->phone_number}}</td>
            <td>{{ $submittedTest->email}}</td>
            <td>{{ $submittedTest->address}}</td>
            <td>{{ $submittedTest->work_experience}}</td>
            <td>{{ $submittedTest->birth_date}}</td>
            <td>{{ $submittedTest->id_number}}</td>


        </tr>
    @endforeach
    </tbody>
</table>
