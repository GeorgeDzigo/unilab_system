@extends(backpack_view('blank'))


@section('content')
<h6>@lang('დეპარტამენტი') : {{ $department->name  }}</h3>
<h6>@lang('კონკურსი') : {{ $competition->title }}</h3>
<div class="container dark-grey-text mt-5">

    <form action="{{ route('attachTest', $competition->id) }}" method="POST" id="announcedCompetitionForm">
        @csrf
        <input type="hidden" id='test-id' name="test_id" value="">
        <input type="hidden" id='competition-id' name="competition_id" value="{{ $competition->id }}">
        <input type="hidden" id='department-id' name="department_id" value="{{ $department->id }}">
    </form>
      <div class="row">
        <h4>Your tests</h4>
        <div class="col-md-12 mb-8 overflow-auto" style='height:300px;'>
            @foreach ($userTests as $userTest)
            <div class="card" id="test_{{ $userTest->id }}_parent">
                <div class="card-body">
                    <h5 class="card-title">@lang('ტესტის სათაური'): <span class="test-title" style='margin:5px'>{{ $userTest->title }}</span></h5>
                    <p id="test_{{$userTest->id}}" class="btn btn-success" onclick="addTest(this)">@lang('ტესტის მიბმა')</p>
                </div>
            </div>
        @endforeach
        </div>

      </div>

      <div class="row added-tests-wrapper">

        <div class="col-md-12 mb-8 overflow-auto added-tests-container" style='height:150px;'>

            @if ($attachedTest)
            @php
                $attachedTest = $attachedTest->test;
            @endphp
            <div class="added_test added_test_{{$attachedTest->id}}" id="{{ $attachedTest->id }}">
                @if ($attachedTest->user_id == auth()->id())
                    <span class="closeBTN" id="closeBTN_test_{{$attachedTest->id}}" style='
                    font-weight: 900;
                    cursor: pointer;
                    float: right;
                    font-size: 15px;'
                    onclick="deleteAttachedTest(this)"
                    >X</span>

                @endif
                <div class="card" id="test_{{ $attachedTest->id }}_parent">
                    <div class="card-body">
                        <h5 class="card-title">@lang('ტესტის სათაური'): <span class="test-title" style='margin:5px'>{{ $attachedTest->title }}</span></h5>
                    </div>
                </div>
            </div>
            @endif
        </div>
      </div>
      <button class="btn btn-primary" onclick="document.getElementById('announcedCompetitionForm').submit();">@lang('შენახვა')</button>
    </div>


@endsection


@push('after_scripts')
<script>
    function addTest(testBTN)
    {
        let test = document.getElementById(testBTN.id + '_parent');
        let addedTestsContainer = document.querySelector('.added-tests-container');
        let alreadyAddedTests = document.querySelectorAll('.added_test');

        if(alreadyAddedTests.length < 1) {
            addedTestsContainer.innerHTML += `<div class="added_test added_${testBTN.id}" id="${testBTN.id.split("_")[1]}">
            <span class="closeBTN" id="closeBTN_test_${ testBTN.id.split("_")[1] }" style='
                font-weight: 900;
                cursor: pointer;
                float: right;
                font-size: 15px;'
                onclick="deleteAttachedTest(this)"
            >X</span>
                ${test.outerHTML}
        </div>`;
        $('.added_'+testBTN.id).find('#'+testBTN.id).remove();
        getTestIdAndApplyInTestDataInput();
        }
    }

    function deleteAttachedTest(test)
    {
        let testIdInArr = test.id.split("_");
        testIdInArr[0] = 'added';
        testIdInArr = testIdInArr.join("_");

        $("."+testIdInArr).remove();
        getTestIdAndApplyInTestDataInput();

    }

    function getTestIdAndApplyInTestDataInput()
    {
        let attachedTest = document.querySelector('.added_test');
        if(attachedTest != null)
            document.getElementById('test-id').value = attachedTest.id;
        else
            document.getElementById('test-id').value = null;


    }
    getTestIdAndApplyInTestDataInput();




</script>
@endpush
