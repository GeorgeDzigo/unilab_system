<div class="upload-options">
    <img src="/img/icons/plus-solid.svg" alt="plus">
    <label>
        <input type="file" name='{{$name}}' id="{{ $name }}" class="image-upload" accept="image/*">
    </label>
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
