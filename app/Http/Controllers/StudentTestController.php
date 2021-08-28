<?php

namespace App\Http\Controllers;

use App\Models\AttachedTests;
use App\Models\SubmittedTest;
use App\Models\SubmittedTestAnswers;
use App\Models\TestQuestions;
use Illuminate\Http\Request;

class StudentTestController extends Controller
{
    /**
     * Show Test
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        $id = explode('-', $slug)[0];
        if(AttachedTests::find($id)) {
            $attachedTest = AttachedTests::where('id', $slug)->first();
            $competition = $attachedTest->competition;
            if(date('Y-m-d H:m:s') >= $competition->end_date) return view('test.expired');

            $department = $attachedTest->department;
            $test = $attachedTest->test;
            return view('test.test', [
                'attachedTest' => $attachedTest,    'competition' => $competition,
                'department' => $department,        'test' => $test,

                'default_questions' =>  $test->questions
                        ->filter(function($question) { return $question->is_default ;})
                        ->map(function($question)   { return $question->question; }),

                'questions' => $test->questions
                            ->filter(function($question) {  return !$question->is_default; })
                            ->map(function($question)  { return $question->question; }),

                'slug' => $slug,
                'id_number' => TestQuestions::where('unique_id' , 'id_number')->first(),
            ]);
        }
        abort(404, 'Test not Found');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  slug  $request
     * @return \Illuminate\Http\Response
     */
    public function store($slug, Request $request)
    {
        $id = explode('-', $slug)[0];
        $attachedTest = AttachedTests::find($id);
        if(SubmittedTest::where('test_id', $attachedTest->test_id)->where('id_number', $request->get('id_number'))->first()) {
            return redirect(route('alreadySubmitted', $slug));
        }

                        /* VALIDATION */
        // Default Questions
        $default_questions = ['first_name', 'last_name', 'phone_number', 'email', 'address', 'work_experience', 'birth_date', 'id_number'];

        $defaultQuestions = collect($request->all())->filter(function($value, $key) use($default_questions) {
            return in_array($key, $default_questions);
        });

        $defaultQuestionsToSubmit = $defaultQuestions;
        $toValidate = $defaultQuestions->map(function($value, $key) { return  $value = 'required'; });

        // Attached Questions validation
        $attachedQuestions = $attachedTest->test->questions;
        $attachedQuestionsToValidate = collect();
        $attachedQuestionToSubmit = collect();
        foreach($attachedQuestions as $attachedQuestion) {
            $attachedQuestion =  $attachedQuestion->question;
            if($attachedQuestion->user_id != null) {
                $attachedQuestionToSubmit->push($attachedQuestion->unique_id);
                if(!$attachedQuestion->is_optional) $attachedQuestionsToValidate->push($attachedQuestion->unique_id);
            }
        }
        $attachedQuestionsToValidate = $attachedQuestionsToValidate->mapWithKeys(function($value, $key) {
            return [$value => 'required'];
        });
        $attachedQuestionsToValidate->each(function($item, $key) use($toValidate) {
            $toValidate[$key] = $item;
        });
        $request->validate($toValidate->toArray());

        $submittedTest = SubmittedTest::create($defaultQuestionsToSubmit->toArray());
        $submittedTest->department_id = $attachedTest->department_id;
        $submittedTest->competition_id = $attachedTest->competition_id;
        $submittedTest->test_id = $attachedTest->test_id;
        $submittedTest->phone_number = $request->get('phone_number');
        $submittedTest->id_number = $request->get('id_number');

        $submittedTest->save();

        // Attached Questions

        foreach($attachedQuestionToSubmit as $unique_id) {
            $question = TestQuestions::where('unique_id', $unique_id)->first();
            if($question->option_type_key != 'multi_select') {
                $submittedTestAnswers = new SubmittedTestAnswers();
                $submittedTestAnswers->submitted_test_id = $submittedTest->id;
                $submittedTestAnswers->question_id = $question->id;
                $submittedTestAnswers->question_name = $question->question_title;
                $submittedTestAnswers->option_type = $question->option_type_key;
            }
            if($question->option_type_key == 'file_upload') {
                $newFileName = time() . '-' . $question->id . "." . $request->$unique_id->extension();
                $path = public_path('/storage/submittedTests/');
                $request->$unique_id->move($path, $newFileName);
                $submittedTestAnswers->answer = "/storage/submittedTests/$newFileName";
                $submittedTestAnswers->save();
            }
            else if($question->option_type_key == 'multi_select') {
                foreach($request->get($unique_id) as $answer) {
                    $submittedMultiTest = new SubmittedTestAnswers();
                    $submittedMultiTest->submitted_test_id = $submittedTest->id;
                    $submittedMultiTest->question_id = $question->id;
                    $submittedMultiTest->question_name = $question->question_title;
                    $submittedMultiTest->answer = $answer;
                    $submittedMultiTest->option_type = $question->option_type_key;
                    $submittedMultiTest->save();
                }
            }
            else
                $submittedTestAnswers->answer = $request->get($unique_id);
            $submittedTestAnswers->save();
        }
        return redirect(route('test.success'));
    }

    /**
     *  Success
     *
     */
     public function success()
     {
         return view('test.send_success');
     }
}
