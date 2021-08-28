@php
    $usersQuestions = $field['model']::where('user_id', backpack_user()->id)->get();

    if(Route::current()->action["operation"] == "update") {
        $toShow = collect();
        $test = $field['mainModel']::find($id);
        foreach($test->questions as $question) {
                if(!$question->is_default)
                    $toShow->push($question->question_id);
            }
    }
@endphp

<!-- field_type_name -->
<div @include('crud::inc.field_wrapper_attributes')>
    <label>{!! $field['label'] !!}</label>

    <input type="hidden" name="test-data" id="test-data">
    <div class="container overflow-auto" style="height: 300px; border: 1px solid #C1C1C1;" >
        @foreach ($usersQuestions as $usersQuestion)
            <div class="card" id="question_{{ $usersQuestion->id }}_parent"
                @if (Route::current()->action["operation"] == "update")
                    @if ($toShow->contains($usersQuestion->id))
                        style="display: none"
                    @endif
                @endif
                >
                <div class="card-body">
                    <h5 class="card-title">{{ $usersQuestion->question_title }}</h5>
                    <div style="margin-left: 20px">
                        {!! $usersQuestion->question_title_explanation !!}
                    </div>
                    <h6>@lang('კითხვის ტიპი') : {{ $usersQuestion->option_type }}</h6>
                    <p id="question_{{$usersQuestion->id}}" class="btn btn-success" onclick="addQuestion(this)">@lang('თქვენი კითხვის დამატება')</p>
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
    <label> @lang('თქვენი დამატებული კითხვები') </label>
    <div class="container questionsContainer">
        <ol class="sortable" onclick="testDataToJson()">

        @if (Route::current()->action["operation"] == "update")
            @php
                $test = $field['mainModel']::find($id);
                $testQuestions = collect($test->questions);
            @endphp

            @foreach ($test->questions as $testQuestion)
                @if ($testQuestion->is_default != True)
                @php
                    $testQuestion = $testQuestion->question;
                @endphp
                <li>
                    <div class="added-question" id="{{$testQuestion->id}}">
                        <span class="closeBTN" onclick="deleteRow(this)">X</span>
                        <div class="card" id="question_{{ $testQuestion->user_id }}_parent">
                            <div class="card-body">
                                <h5 class="card-title">{{ $testQuestion->question_title }}</h5>
                                <div style="margin-left: 20px">
                                    {!! $testQuestion->question_title_explanation !!}
                                </div>
                                <h6>@lang('კითხვის ტიპი'): {{ $testQuestion->option_type }}</h6>
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

        .sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
        /* .sortable > li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; } */
        /* .sortable > li > span { position: absolute; margin-left: -1.3em; } */
    </style>
    @endpush

    {{-- FIELD EXTRA JS --}}
    {{-- push things in the after_scripts section --}}
    @push('crud_fields_scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        $( function() {
            $( ".sortable" ).sortable();
            $( ".sortable" ).disableSelection();
        });

    </script>

        <script>
            function addQuestion(btn)
            {
                let questionsContainer = document.querySelector('.sortable');
                let question = document.getElementById(btn.id+'_parent');

                questionsContainer.innerHTML += `
                <li>
                    <div class="added-question added_${btn.id}" id="${btn.id.split("_")[1]}">
                        <span class="closeBTN" onclick="deleteRow(this)">X</span>
                        ${question.outerHTML}
                    </div>
                </li>`;
                $('.added_' + btn.id).find("#"+btn.id).remove();
                testDataToJson();
                question.style.display = "none";
            }

            function deleteRow(element) {
                let parentDivId = element.closest('div').id;
                element.closest('li').remove();
                document.querySelectorAll("#question_"+ parentDivId +"_parent")[0].style = '';
                testDataToJson();
            }

            function testDataToJson()
            {
                let addedQuestions = document.querySelectorAll(".added-question");
                let testData = document.getElementById('test-data');

                let json = {};
                for(let i = 0; i < addedQuestions.length; i++)
                {
                    json[i] = addedQuestions[i].id;

                }
                testData.value = JSON.stringify(json);
            }

            document.querySelectorAll('.added-question').forEach(v => v.addEventListener('mouseup', function() {

                setTimeout(function(){
                    testDataToJson();
                }, 100);
            }));
            testDataToJson();
        </script>
    @endpush
@endif
