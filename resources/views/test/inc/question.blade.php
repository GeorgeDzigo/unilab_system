<div id="name" class="content-box">
    <label for="{{ $question->unique_id }}">
        {{ $question->question_title }}
        @if (!$question->is_optional)
            <span style='color:red;'>*</span>
        @endif
    </label>
    <div class="input-cont">
        @include('/test/inc/questionTypes/' . $question->option_type_key, [
            'answers' => $question->options,
            'name' => $question->unique_id,
            'explanation' => $question->explanation,
            'default_question' => $question->user_id == null,
            ])

        @if ($question->question_file || $question->question_image)
        <div style='
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-direction: row;
        '>
            @if ($question->question_file)
                <a class="link__style" href="../{{ $question->question_file }}" target="_blank">
                    მიმაგრებული ფაილის ნახვა
                </a>
            @endif
            @if ($question->question_image)
                <a class="link__style" href="../{{ $question->question_image }}" target="_blank">
                    მიმაგრებული სურათის ნახვა
                </a>
            @endif
        </div>
        @endif
    </div>
</div>
