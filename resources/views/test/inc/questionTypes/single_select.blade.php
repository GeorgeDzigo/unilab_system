<div class='single-select'>
    @php
        $s = 0;
    @endphp
    @foreach ($answers as $answer)
    <div class="radio-box">
        <label for="{{ $name }}-{{ $answer->id }}" class="container">
            <input
                type="radio"
                name="{{ $name }}"
                id="{{ $name }}-{{ $answer->id }}"
                value="{{ $answer->option_select_title }}"
                @if (old($name) && $answer->option_select_title == old($name))
                    checked
                @endif
            >{{ $answer->option_select_title }}

            <span class="checkmark radio-input"></span>
        </label>
    </div>
    @php
        $s++;
    @endphp
    @endforeach
    @error($name)
    <div class="Ntooltip">
        <label
            id="username-error"
            class="error" for="username"
            style="display: inline;"

        >
            {{ $question->question_title }} is required
        </label>
        <div class="errorImage"></div>
    </div>
@enderror
</div>
