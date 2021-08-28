<div class='multi_select'>
    @php
        $m = 0;
    @endphp
    @foreach ($answers as $answer)
        <label for="{{ $name }}-{{ $answer->id }}" class="container">
            <input type="checkbox"
                name="{{ $name }}[]"
                id="{{ $name }}-{{ $answer->id }}"
                value="{{ $answer->option_select_title }}"
                @if (is_array(old($name)) && isset(old($name)[$m]) && $answer->option_select_title == old($name)[$m])
                    checked
                @endif
                >{{ $answer->option_select_title }}
                <span class="checkmark"></span>
        </label>
        @php
            $m++;
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
