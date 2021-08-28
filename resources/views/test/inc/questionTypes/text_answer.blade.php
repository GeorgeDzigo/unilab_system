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
@if (!$default_question)
    <textarea
    name="{{ $name }}"
    id="{{ $name }}"
    cols="30"
    rows="10"
    class="form_input input-animation error"
    aria-invalid="true"
    style="
    height: 90px;
    resize: vertical;
    "
    >{{old($name)}}</textarea>
@else
    <input
        type="text"
        name="{{ $name }}"
        id="{{ $name }}"
        class="form_input input-animation error"
        aria-invalid="true"
        value="{{ old($name) }}"
    >
@endif
