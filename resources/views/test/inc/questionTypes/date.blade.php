<input type="date" id="{{ $name }}" class="form_input date-valid error" name="{{ $name }}" aria-invalid="true">
@error($name)
<div class="Ntooltip">
    <label id="start_day-error" class="error" for="start_day" style="display: inline;" value="{{ old($name) }}">
    {{ $question->question_title }} is required
</label>
    <div class="errorImage"></div>
</div>
@enderror
