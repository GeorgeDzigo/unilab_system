@php
    $operationIsEdit = Route::current()->action["operation"] == "update";

    if(isset($field['model'], $field['pivotModel']) &&  $operationIsEdit) {
        $question = $field['model']::find($id);
        $answers = $field['pivotModel']::where('question_id', $question->id)->get();
        $jsonFormatAnswers = json_encode($answers);
        $optionType = $question->option_type;
        $trimmedOptionType = implode("_", explode(" ", $optionType));
    }
@endphp

<!-- field_type_name -->
<div @include('crud::inc.field_wrapper_attributes') id="options-parent">
    <label>{!! $field['label'] !!}</label>

    @if (isset($field['options']))
        <select
            name="{{ $field['name'] }}"
            id="select"
            @include('crud::inc.field_attributes')

            onchange="showField(this.value)"
        >
        <option selected >----</option>
            @foreach ($field['options'] as $option)
                    @if (isset($field['model'], $field['pivotModel']) && $operationIsEdit && $option['label'] == $optionType)
                        <option value="{{ $option['label'] }}" selected>{{ $option['label'] }}</option>
                    @else
                        <option value="{{ $option['label'] }}">{{ $option['label'] }}</option>
                    @endif
            @endforeach
        </select>

    @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>
<div class="form-group col-sm-12 selectorContainer">

</div>

@push('crud_fields_styles')
    <!-- no styles -->
@endpush

{{-- FIELD EXTRA JS --}}
{{-- push things in the after_scripts section --}}
@push('crud_fields_scripts')
    <script type="text/javascript" src="{{ asset('packages/jquery-ui-dist/jquery-ui.min.js') }}"></script>

    <script>
        const SavedFields = document.querySelectorAll(".toShow");
        SavedFields.forEach(field => {
            field.remove();
        })
        function showField(value) {
            let selectorContainer = document.querySelector(".selectorContainer");
            value = value.split(" ").join("_");
            selectorContainer.innerHTML = "";

            SavedFields.forEach((field, index) => {
                if(field.id == value) {
                    selectorContainer.innerHTML = `<div class="form-group col-sm-12 showedType" id="${field.id}">${field.innerHTML}</div>`
                    initCustomTableFunction(field.id);
                    return;
                }
            });
        }
    </script>
    @if (isset($field['model'], $field['pivotModel']) &&  $operationIsEdit)
        {!! "<script>showField('$optionType')</script>"!!}

        <script>

            function onEditFunction(value, onEdit = '') {
                let parent = $("#" + value);
                let addItemButton = parent.find("[data-button-type=addItem]")

                 addItemButton.click(function() {
                     addRow();
                 })

                function addRow() {
                     parent.find("[data-field-type=table]")
                     .append(parent.find(".clonable")
                     .clone()
                     .show()
                     .addClass('cloned')
                     .removeClass("clonable")
                     );
                }

                if(onEdit != '') {
                    let jsonToObject = JSON.parse(onEdit);
                    for(let i = 0; i < jsonToObject.length; i++) {
                        addRow();
                    }

                    let rows = document.querySelectorAll('.cloned');

                    for(let i = 0; i < rows.length; i++) {
                        let row = rows[i];
                        let answer = jsonToObject[i];
                        row.querySelector('.title').value = answer.option_select_title;
                        row.querySelector('.checkbox').checked = answer.option_is_correct;

                    }
                }
                function JSONupdate() {
                    let clonedRows = document.querySelectorAll(".cloned");
                    let jsonToSave = document.querySelector(".array-json");
                    let toJSON = {};

                    clonedRows.forEach((row, i) => {
                        toJSON[i] = {
                            "title" : row.querySelector('.title').value,
                            "correct" : row.querySelector('.checkbox').checked,
                        }
                    });
                    jsonToSave.value = JSON.stringify(toJSON);
                }
                JSONupdate();
            }
        </script>
            {!!  "<script>onEditFunction('$trimmedOptionType', '$jsonFormatAnswers'); JSONupdate()</script>"  !!}

    @endif

@endpush
