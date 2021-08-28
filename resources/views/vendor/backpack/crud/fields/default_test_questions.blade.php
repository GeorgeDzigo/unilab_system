@php
    $defaultTestQuestions = collect($field['model']::where('user_id', null)->get());
    $defaultTestQuestions = $defaultTestQuestions->filter(function($value) {
        return $value->unique_id != "id_number";
    });
    if(Route::current()->action["operation"] == "update") {
        $toShowDefault = collect();
        $test = $field['mainModel']::find($id);
        foreach($test->questions as $question) {
                if($question->is_default)
                    $toShowDefault->push($question->question_id);
            }
    }
@endphp

<!-- field_type_name -->
<div @include('crud::inc.field_wrapper_attributes')>
    <label>{!! $field['label'] !!}</label>

    <input type="hidden" name="default-test-data" id="default-test-data">
    <div class="container overflow-auto" style="height: 300px; border: 1px solid #C1C1C1;" >
        @foreach ($defaultTestQuestions as $defaultTestQuestion)
            <div class="card" id="default_question_{{ $defaultTestQuestion->id }}_parent"
                @if (Route::current()->action["operation"] == "update")
                    @if ($toShowDefault->contains($defaultTestQuestion->id))
                        style="display: none"
                    @endif
                @endif
                >
                <div class="card-body">
                    <h5 class="card-title">{{ $defaultTestQuestion->question_title }}</h5>
                    <div style="margin-left: 20px">
                        {!! $defaultTestQuestion->question_title_explanation !!}
                    </div>
                    <h6>@lang('კითხვის ტიპი'): {{ $defaultTestQuestion->option_type }}</h6>
                    <p id="default_question_{{$defaultTestQuestion->id}}" class="btn btn-success" onclick="addDefaultQuestion(this)">@lang('ნაგულისხმევი კითხვის დამატება')</p>
                </div>
            </div>
        @endforeach
    </div>
    <br>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>
<div @include('crud::inc.field_wrapper_attributes')>
    <label> @lang('დამატებული ნაგულისხმევი კითხვები')</label>
    <div class="container defaultQuestionsContainer">
        <ol class="defaultQuestionsSortable" onclick="defaultTestDataToJson()">

        @if (Route::current()->action["operation"] == "update")
            @php
                $test = $field['mainModel']::find($id);
                $defaultTestQuestions = collect($test->questions);

            @endphp



            @foreach ($defaultTestQuestions as $defaultTestQuestion)

            @if ($defaultTestQuestion->is_default == True)
                @php

                    $defaultTestQuestion = $defaultTestQuestion->question;

                @endphp

            <li>
                <div class="added-default-question" id="{{$defaultTestQuestion->id}}">
                    <span class="closeBTN" onclick="deleteDefaultRow(this)">X</span>
                    <div class="card" id="default_question_{{ $defaultTestQuestion->user_id }}_parent">
                        <div class="card-body">
                            <h5 class="card-title">{{ $defaultTestQuestion->question_title }}</h5>
                            <div style="margin-left: 20px">
                                {!! $defaultTestQuestion->question_title_explanation !!}
                            </div>
                            <h6>@lang('კითხვის ტიპი') : {{ $defaultTestQuestion->option_type }}</h6>
                        </div>
                    </div>
                </div>
            </li>
            @endif

            @endforeach
        @endif
        </ol>

    </div>
</div>
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

        .defaultQuestionsSortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
    </style>
    @endpush

    {{-- FIELD EXTRA JS --}}
    {{-- push things in the after_scripts section --}}
    @push('crud_fields_scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        $( function() {
            $( ".defaultQuestionsSortable" ).sortable();
            $( ".defaultQuestionsSortable" ).disableSelection();
        });

    </script>

        <script>
            function addDefaultQuestion(btn)
            {
                let questionsContainer = document.querySelector('.defaultQuestionsSortable');
                let question = document.getElementById(btn.id+'_parent');

                questionsContainer.innerHTML += `
                <li>
                    <div class="added-default-question added_default_${btn.id}" id="${btn.id.split("_")[2]}">
                        <span class="closeBTN"  onclick="deleteDefaultRow(this)">X</span>
                        ${question.outerHTML}
                    </div>
                </li>`;

                $('.added_default_' + btn.id).find("#"+btn.id).remove();
                defaultTestDataToJson();
                question.style.display = "none";
            }

            function deleteDefaultRow(element) {
                let parentDivId = element.closest('div').id;
                element.closest('li').remove();
                document.querySelectorAll("#default_question_"+ parentDivId +"_parent")[0].style = '';
                defaultTestDataToJson();
            }

            function defaultTestDataToJson()
            {
                let addedQuestions = document.querySelectorAll('.added-default-question');
                let testData = document.getElementById('default-test-data');

                let json = {};
                for(let i = 0; i < addedQuestions.length; i++)
                {
                    json[i] = addedQuestions[i].id;
                }

                testData.value = JSON.stringify(json);

            }

            document.querySelectorAll('.added-default-question').forEach(v => v.addEventListener('mouseup', function() {

                setTimeout(function(){
                    defaultTestDataToJson();
                }, 100);
            }));
            defaultTestDataToJson();


        </script>
    @endpush
@endif
