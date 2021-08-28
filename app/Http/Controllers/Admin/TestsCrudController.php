<?php
namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

use Illuminate\Support\Str;

// Models
use App\Models\Test;
use App\Models\TestQuestions;
use App\Models\QuestionsInTest;

// Request

use App\Http\Requests\admin\testRequest\testRequest;
use App\Http\Requests\Admin\QuestionsTest\QuestionsTest;
use phpDocumentor\Reflection\PseudoTypes\True_;

class TestsCrudController extends CrudController {

  use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup ()
    {
        $this->crud->setModel(Test::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/tests');
        $this->crud->setEntityNameStrings(__('ტესტები'), __('ტესტები'));
        $this->crud->addClause('where', 'user_id', backpack_user()->id);

    }

    public function setupListOperation()
    {
        $this->commonColumns();

    }

    public function setupCreateOperation()
    {
        $this->crud->setValidation(testRequest::class);
        $this->commonFields();
    }

    public function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function commonColumns()
    {
        $columns = [
            [
                'name' => 'title',
                'type' => 'text',
                'label' => 'ტესტის სათაური'
            ],
        ];
        foreach($columns as $column)
        {
            $this->crud->addColumn($column);
        }
    }
    protected function commonFields()
    {

        $fields = [
            [
                'name' => 'title',
                'type' => 'text',
                'label' => 'ტესტის სათაური',
            ],
            [
                'name' => 'default_test_questions',
                'type' => 'default_test_questions',
                'label' => 'ნაგულისხმევი კითხვები',
                'except' => 'id_number',
                'model' => TestQuestions::class,
                'mainModel' => Test::class,
            ],

            [
                'name' => 'user_test_questions',
                'type' => 'user_test_questions',
                'label' => 'თქვენი კითხვები',
                'model' => TestQuestions::class,
                'mainModel' => Test::class,
            ],
        ];
        foreach($fields as $field)
        {
            $this->crud->addField($field);
        }
    }

    protected function store(testRequest $testRequest)
    {
        $test = new Test();
        $test->user_id = backpack_user()->id;
        $test->title = $testRequest->get('title');
        $test->unique_id = Str::random(9);
        $test->save();

        $testId = Test::orderBy('id', 'DESC')->first()->id;


        foreach(json_decode($testRequest->get('default-test-data')) as $key => $questionId)
        {
            $testsDefaultQuestions = new QuestionsInTest();

            $testsDefaultQuestions->test_id = $testId;
            $testsDefaultQuestions->question_id = $questionId;
            $testsDefaultQuestions->sort = 1 + $key;
            $testsDefaultQuestions->is_default = true;


            $testsDefaultQuestions->save();
        }

        foreach(json_decode($testRequest->get('test-data')) as $key => $questionId)
        {
            $questionsInTest = new QuestionsInTest();

            $questionsInTest->test_id = $testId;
            $questionsInTest->question_id = $questionId;
            $questionsInTest->sort = 1 + $key;

            $questionsInTest->save();
        }

        return redirect('admin/tests');
    }

    protected function update(testRequest $testRequest)
    {
        $test = Test::find($testRequest->get('id'));
        $test->update([
            'title' => $testRequest->get('title')
        ]);
        $test->save();

        $questions = QuestionsInTest::where('test_id', $testRequest->get('id'))->get();

        $testsDefaultQuestions = QuestionsInTest::where('is_default', True)->get();

        foreach ($questions as $question) QuestionsInTest::destroy($question->id);

        foreach ($testsDefaultQuestions as $testsDefaultQuestion) QuestionsInTest::destroy($testsDefaultQuestion->id);



        foreach(json_decode($testRequest->get('default-test-data')) as $key => $questionId)
        {
            $testsDefaultQuestions = new QuestionsInTest();

            $testsDefaultQuestions->test_id = $testRequest->get('id');
            $testsDefaultQuestions->question_id = $questionId;
            $testsDefaultQuestions->sort = 1 + $key;
            $testsDefaultQuestions->is_default = true;

            $testsDefaultQuestions->save();
        }

        foreach(json_decode($testRequest->get('test-data')) as $key => $questionId)
        {
            $questionsInTest = new QuestionsInTest();
            $questionsInTest->test_id = $testRequest->get('id');

            $questionsInTest->question_id = $questionId;
            $questionsInTest->sort = 1 + $key;

            $questionsInTest->save();
        }
        return redirect('admin/tests');

    }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        $questions = QuestionsInTest::where('test_id', $id)->get();
        foreach ($questions as $question) QuestionsInTest::destroy($question->id);

        return $this->crud->delete($id);
    }

}
