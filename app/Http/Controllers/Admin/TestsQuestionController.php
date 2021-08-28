<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

// requests
use App\Http\Requests\Admin\QuestionsTest\QuestionsTest;
use App\Http\Requests\Admin\TestQuestionsAnswer\TestQuestionsAnswersRequest;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// Models
use App\Models\TestQuestions;
use App\Models\TestQuestionsAnswers;

class TestsQuestionController extends CrudController {

  use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;


    public function setup ()
    {
        $this->crud->setModel(TestQuestions::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/testsquestions');
        $this->crud->setEntityNameStrings(__('კითხვები'), __('კითხვები'));
        $this->crud->addClause('where', 'user_id', backpack_user()->id);
        $this->crud->addClause('where', 'id', ">", 7);

    }

    protected function setupListOperation ()
    {
        // TODO: remove setFromDb() and manually define Columns, maybe Filters
        $columns = [
            [
                'name' => 'question_title',
                'type' => 'text',
                'label' => __('სათაური'),
            ],

            [
                'name' => 'question_title_explanation',
                'type' => 'text',
                'label' => __('განმარტება'),
            ],

            [
                'name' => 'question_file',
                'type' => 'file',
                'label' => __('ფაილი'),
            ],

            [
                'name' => 'question_image',
                'type' => 'image',
                'label' => __('სურათი'),
            ],

            [
                'name' => 'question_image_explanation',
                'type' => 'text',
                'label' => __('სურათის აღწერა'),
            ],

            [
                'name' => 'option_type',
                'label' => __('პასუხის ტიპი'),
                'type' => 'custom_text',
                'function_name' => 'options'
            ],

            [
                'label' => __('პასუხები'),
                'type' => 'custom_table',
                'function_name' => 'options'
            ],
        ];

        foreach($columns as $column) {
        $this->crud->addColumn($column);
        }
    }

    protected function setupCreateOperation()
    {
        $this->commonFields();
        $this->crud->setValidation(QuestionsTest::class);
        $this->crud->setValidation(TestQuestionsAnswersRequest::class);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false);

        $this->setupListOperation();
    }

    /**
     *
     * Overwriting the store method
     * Storing submitted data in test_questions and in test_questions_answers tables
     */
    public function store (QuestionsTest $QuestionTestsRequest, TestQuestionsAnswersRequest $testQuestionsAnswersRequest)
    {
        $testsQuestion = new TestQuestions();
        $testsQuestion->user_id = backpack_user()->id;
        $testsQuestion->question_title = $QuestionTestsRequest->get('question_title');

        // file
        if($QuestionTestsRequest->has('question_file')) {
            $newFileName = backpack_user()->id . "_" . time(). '.' . $QuestionTestsRequest->question_file->extension();
            $QuestionTestsRequest->question_file->move(public_path('storage/uploads/files/'), $newFileName);
            $testsQuestion->question_file = 'storage/uploads/files/' . $newFileName;
        }
        // Image
        if($QuestionTestsRequest->has('question_image')) {
            $newImageName = backpack_user()->id . "_" . time(). '.' . $QuestionTestsRequest->question_image->extension();
            $QuestionTestsRequest->question_image->move(public_path('storage/uploads/images/'), $newImageName);
            $testsQuestion->question_image = 'storage/uploads/images/' . $newImageName;
        }

        $testsQuestion->question_title_explanation = $QuestionTestsRequest->get('question_title_explanation');
        $testsQuestion->question_image_explanation = $QuestionTestsRequest->get('question_image_explanation');
        $testsQuestion->is_optional = $QuestionTestsRequest->get('is_optional');
        $testsQuestion->option_type = $QuestionTestsRequest->get('option_type');
        $testsQuestion->option_type_key = implode("_", explode(" ", strtolower($QuestionTestsRequest->get('option_type'))));
        $testsQuestion->unique_id = Str::random(9);
        $testsQuestion->save();

        // Test question answers table
        $questionId = $testsQuestion->orderBy('id', 'DESC')->first()->id;

        if($testQuestionsAnswersRequest->has('selections_data'))
            foreach(json_decode($testQuestionsAnswersRequest->get('selections_data')) as $selectionData)
                $this->TestQuestionsAnswersCreate($questionId, $selectionData->title, $selectionData->correct);

        return redirect("admin/testsquestions");
    }

    /**
     *
     * Overwriting the question method
     * Updating given question
     */
    protected function update (QuestionsTest $QuestionTestsRequest, TestQuestionsAnswersRequest $testQuestionsAnswersRequest)
    {
        $questionId = $QuestionTestsRequest->get('id');
        $testQuestion = TestQuestions::find($questionId);

        // file
        if($QuestionTestsRequest->has('question_file')) {
            File::delete(public_path($testQuestion->question_file));
            $newFileName = backpack_user()->id . "_" . time(). '.' . $QuestionTestsRequest->question_file->extension();

            $QuestionTestsRequest->question_file->move(public_path('storage/uploads/files'), $newFileName);
            $testQuestion->update([
                'question_file' => 'storage/uploads/files/' . $newFileName,
            ]);
        }

        // Image
        if($QuestionTestsRequest->has('question_image')) {
            File::delete(public_path($testQuestion->question_image));
            $newImageName = backpack_user()->id . "_" . time(). '.' . $QuestionTestsRequest->question_image->extension();

            $QuestionTestsRequest->question_image->move(public_path('storage/uploads/images'), $newImageName);
            $testQuestion->update([
                'question_image' => 'storage/uploads/images/' . $newImageName,
            ]);
        }


        $testQuestion->update([
            'question_title' => $QuestionTestsRequest->get('question_title'),
            'question_title_explanation' => $QuestionTestsRequest->get('question_title_explanation'),
            'question_image_explanation' => $QuestionTestsRequest->get('question_image_explanation'),
            'is_optional' => $QuestionTestsRequest->get('is_optional'),
            'option_type' => $QuestionTestsRequest->get('option_type'),
            'option_type_key' => implode("_", explode(" ", strtolower($QuestionTestsRequest->get('option_type')))),
            'unique_id' => Str::random(9),
        ]);


        $testQuestionsAnswers = TestQuestionsAnswers::where('question_id' , $questionId)->get();

        foreach($testQuestionsAnswers as $testQuestionsAnswer)
            TestQuestionsAnswers::destroy($testQuestionsAnswer->id);

        if($testQuestionsAnswersRequest->has('selections_data'))
            foreach(json_decode($testQuestionsAnswersRequest->get('selections_data')) as $selectionData)
                $this->TestQuestionsAnswersCreate($questionId, $selectionData->title, $selectionData->correct);

        return redirect("admin/testsquestions");
    }
    /**
     *
     * Overwriting destroy method
     * Finding the question in the test_questions table with given id and deleting it with its files/images
     */
    protected function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $question = TestQuestions::find($id);

        $questionOptions = TestQuestionsAnswers::where('question_id', $id)->get();
        foreach($questionOptions as $questionOption)
            TestQuestionsAnswers::destroy($questionOption->id);

        $questionFile = $question->question_file;
        $questionImage = $question->question_image;

        File::delete(public_path($questionFile));
        File::delete(public_path($questionImage));

        return $this->crud->delete($id);
    }


    /**
     *
     * Method is used to create records in test_questions_answers table
     */
    protected function TestQuestionsAnswersCreate ($questionId, $answerTitle = null, $isCorrect = null)
    {
        $testsQuestionAnswers = new TestQuestionsAnswers();
        $testsQuestionAnswers->question_id = $questionId;
        $testsQuestionAnswers->option_select_title = $answerTitle;
        $testsQuestionAnswers->option_is_correct = $isCorrect;
        $testsQuestionAnswers->save();
    }

    /**
     *
     * This method is being used to display fields on edit/create pages.
     */
    protected function commonFields()
    {
                // TODO: remove setFromDb() and manually define Fields
        $fields = [
            [
                'name' => 'question_title',
                'type' => 'text',
                'label' =>  'კითხვის სათაური'
            ],

            [
                'name' => 'question_title_explanation',
                'type' => 'ckeditor',
                'label' =>'კითხვის აღწერა'
            ],

            [
                'name'  => 'question_file',
                'label' => 'კითხვის ფაილი',
                'type'  => 'upload',
                'prefix' => 'storage/',
                'upload' => true,
            ],

            [
                'name'  => 'question_image',
                'label' => 'კითხვის სურათი',
                'type'  => 'upload',
                'prefix' => 'storage/',
                'upload' => true,
            ],

            [
                'name' => 'question_image_explanation',
                'type' => 'ckeditor',
                'label' =>'სურათის აწერა'
            ],

            [
                'name'  => 'is_optional',
                'label' => 'Optional',
                'type'  => 'checkbox'
            ],

            [
                'name'  => 'option_type',
                'label' => 'კითხვის ტიპი',
                'type'  => 'custom_select',
                'model' => TestQuestions::class,
                'pivotModel' => TestQuestionsAnswers::class,
                'options' => [
                    [
                        'label' => 'Text answer',
                    ],

                    [
                        'label' => 'File upload',
                    ],

                    [
                        'label' => 'Single Select',
                    ],

                    [
                        'label' => 'Multi Select',
                    ],
                    [
                        'label' => 'Date',
                    ],
                ],
            ],

            [
                'name' => 'option_single_select_checkbox',
                'multiselect' => false,
                'label' => 'Single Select',
                'type' => 'custom_table',
            ],

            [
                'name' => 'option_multi_select_checkbox',
                'multiselect' => true,
                'label' => 'Multi Select',
                'type' => 'custom_table',
            ],
        ];

        foreach($fields as $field)
        {
            $this->crud->addField($field);
        }
    }
}
