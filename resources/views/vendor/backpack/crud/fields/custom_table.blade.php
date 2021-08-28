<!-- field_type_name -->
<div id="{{ implode("_", explode(" ", $field['label'])) }}" class="form-group col-sm-12 toShow">
    {{-- {{$field['label']}} --}}
    <div
        class="form-group col-sm-12 clonable"
        style='background-color:#FAFAFA; padding: 20px; display:none;'
    >

    <span class="closeBTN" onclick="deleteRow(this)">X</span>

        <div class="form-group col-sm-12 text-input" >
            <label>Title</label>

            <input type="text" value="" class="form-control title" onkeypress="JSONupdate()">
        </div>

        <div class="radio">
            <input type="radio"
            class="checkbox"
            onclick="JSONupdate()"
            @if ($field['multiselect'] != true)
                name="select"
            @endif
            >


            <label class="form-check-label font-weight-normal " for="active_checkbox"> Active</label>
        </div>

    </div>

    <div data-field-type="table" {{-- data-field-name="{{ $field['name'] }}" --}} @include('crud::inc.field_wrapper_attributes') >
        <input
        class="array-json"
        type="hidden"
        name="selections_data"
        value="">

    </div>

    <div class="array-controls btn-group m-t-10">
        <button class="btn btn-sm btn-light" type="button" data-button-type="addItem"><i class="fa fa-plus"></i> Add option</button>
    </div>

</div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif

@if ($crud->fieldTypeNotLoaded($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp

    {{-- FIELD EXTRA CSS  --}}
    {{-- push things in the after_styles section --}}
    @push('crud_fields_styles')
        <style>
            .closeBTN {
                font-weight: 900;
                cursor: pointer;
                float: right;
                font-size: 15px;
            }
            .text-input {
                clear: both;
            }
        </style>
    @endpush

    {{-- FIELD EXTRA JS --}}
    {{-- push things in the after_scripts section --}}
    @push('crud_fields_scripts')
        <script type="text/javascript" src="{{ asset('packages/jquery-ui-dist/jquery-ui.min.js') }}"></script>

        <script>

           function initCustomTableFunction(value) {
               let parent = $("#" + value);
               let addItemButton = parent.find("[data-button-type=addItem]")
               let totalRows = $('.cloned').length;

                addItemButton.click(function() {
                    addRow();
                    totalRows = $('.cloned').length;
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

            function deleteRow(element) {
                element.closest('.cloned').remove();
                JSONupdate()
            }

        </script>
    @endpush
@endif
