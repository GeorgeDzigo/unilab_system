@extends('test.test_layout')

@section('content')
<div class="content">
    <p>{{ $competition->title }} - {{ $test->title }} </p>
    <form method='POST' action="{{route('test.save', $slug)}}" id="auth-form" novalidate="novalidate" enctype="multipart/form-data">
        @csrf
        @foreach ($default_questions as $default_question)
            @include('/test/inc/question', [
                'question' => $default_question
            ])
        @endforeach

        @include('/test/inc/question', [
            'question' => $id_number
        ])

        @foreach ($questions as $question)
            @include('/test/inc/question', [
                'question' => $question
            ])
        @endforeach

        <input id="submit" class="send_btn" type="submit" value="გაგზავნა">
    </form>
</div>
@endsection
