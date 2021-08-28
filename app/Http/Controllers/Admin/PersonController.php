<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Person\CreateRequest;
use App\Models\Department;
use App\Models\PeoplePosition;
use App\Models\Person;
use App\Models\Position;
use App\Models\TempImage;
use App\Repositories\Contracts\IPersonRepository;
use App\Repositories\Contracts\ITempImageRepository;
use App\Utilities\ServiceResponse;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

/**
 * @property TempImage tempImage
 */
class PersonController extends CrudController
{

    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * @throws \Exception
     */
    public function setup()
    {
        $this->crud->setModel(Person::class);
        $this->crud->setRoute( config('backpack.base.route_prefix') . '/person');
        $this->crud->setEntityNameStrings('პირი', 'პირები');
        $this->crud->addButtonFromView('line', 'history', 'history', 'beginning');
        $this->filter();
    }

    /**
     * Filter data.
     */
    public function filter()
    {

        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'filter_name',
            'label' => 'სახელი'
        ],
        false,
        function ($value) {
            $this->crud->addClause('where', 'first_name', 'like', '%' . $value . '%');
        });

        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'filter_last_name',
            'label' => 'გვარი'
        ],
        false,
        function ($value) {
            $this->crud->addClause('where', 'last_name', 'like', '%' . $value . '%');
        });

        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'filter_personal_number',
            'label' => 'პირადი ნომერი'
        ],
            false,
        function ($value) {
            $this->crud->addClause('where', 'personal_number', 'like', '%' . $value . '%');
        });


        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'filter_unilab_email',
            'label' => 'უნილაბის მეილი'
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'unilab_email', 'like', '%' . $value . '%');
            });

        $this->crud->addFilter([
            'type' => 'select2_multiple',
            'name' => 'filter_status',
            'label' => 'სტატუსი'
        ],
        function(){
            return [
                0   => 'გათიშული',
                1   => 'აქტიური'
            ];
        },
        function ($value) {
            $values = json_decode($value, true);

            $this->crud->addClause('whereIn', 'status', $values);
        });

        $this->crud->addFilter([
            'type' => 'select2_multiple',
            'name' => 'filter_positions',
            'label' => 'პოზიცია'
        ],
            function(){
                return Position::getAllKeyValue();
            },
            function ($value) {

                $values = json_decode($value, true);

                $peopleId = PeoplePosition::whereIn('position_id', $values)->pluck('people_id')->toArray();

                $this->crud->addClause('whereIn', 'id', $peopleId);
            });

        $this->crud->addFilter([
            'type' => 'select2_multiple',
            'name' => 'filter_departments',
            'label' => 'მიმართულება'
        ],
            function(){
                return Department::getAllKeyValue();
            },
            function ($value) {

                $values = json_decode($value, true);

                $peopleId = PeoplePosition::whereIn('department_id', $values)->pluck('people_id')->toArray();

                $this->crud->addClause('whereIn', 'id', $peopleId);
            });

        $this->crud->addFilter([
            'type' => 'date_range',
            'name' => 'filter_birth_date',
            'label' => 'დაბადების თარიღი'
        ],
            false,
            function ($value) {

                $dates = json_decode($value);

                $persons = Person::all();

                $personsId = $persons->whereBetween('birthDate', [$dates->from, $dates->to])->pluck('id')->toArray();

                $this->crud->addClause('whereIn', 'id', $personsId );
            });

        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'filter_created_at',
            'label' => __('შექმნის თარიღი')
        ],
            false,
            function ($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'created_at', '>=', Carbon::parse($dates->from));
                $this->crud->addClause('where', 'created_at', '<=', Carbon::parse($dates->to)->subSeconds(59)->addDays(1) );
            });

    }

    /**
     * Setup list operation.
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn([
                'name' => 'full_name',
                'type' => 'model_function',
                'function_name' => 'getFullName',
                'limit' => 1009,
                'label' => __('სახელი/გვარი')
            ]
        );

        $this->crud->addColumn([
            'name' => 'mobile',
            'type' => 'model_function',
            'function_name' => 'getMobile',
            'label' => __('მობილური'),
        ]);

        $this->crud->addColumn([
            'name' => 'personal_number',
            'label' => __('პირადი ნომერი'),
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'unilab_email',
            'label' => __('უნილაბის მეილი'),
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'birth_date',
            'type' => 'model_function',
            'function_name' => 'getBirthDateColumn',
            'limit' => 1009,
            'label' => __('დაბადების თარიღი'),
        ]);

        $this->crud->addColumn([
            'name' => 'status',
            'label' => __('პირის სტატუსი'),
            'type' => 'model_function',
            'function_name' => 'getStatusColumn',
            'limit' => 1009,
        ]);

        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => __('შექმნის თარიღი'),
            'type' => 'text'
        ]);

    }

    /**
     * Show the form for creating inserting a new row.
     *
     * @return Response
     */
    public function create()
    {
        $this->crud->hasAccessOrFail('create');

        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.add').' '.$this->crud->entity_name;

        $this->crud->orderBy('created_at', 'desc');


        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('person.create', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $this->crud->setOperationSetting('fields', $this->crud->getUpdateFields());

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id)->load(['positions']);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;

        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('person.create', $this->data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSaveData()
    {

        try {

            $this->data['routes']['uploadTempImage'] = route('person.upload_temp_image');
            $this->data['routes']['save'] = route('person.save');
            $this->data['routes']['scanner'] = route('scan');
            $this->data['options']['positions'] = Position::all();
            $this->data['options']['departments'] = Department::all();

        } catch (\Exception $ex) {
            return ServiceResponse::jsonNotification($ex->getMessage(), $ex->getCode(), []);
        }

        return ServiceResponse::jsonNotification(__('Get person create data successfully'), 200,  $this->data );
    }


    /**
     * @param CreateRequest $request
     * @param IPersonRepository $person
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(CreateRequest $request, IPersonRepository $person)
    {

        try {

            // Save data.
            $person->saveData($request);

        } catch (\Exception $ex) {
            return ServiceResponse::jsonNotification($ex->getMessage(), $ex->getCode(), []);
        }

        return ServiceResponse::jsonNotification(__('პირი წარმატებით შეინახა'), 200,  $this->data );
    }

    /**
     * Upload image.
     *
     * @param Request $request
     * @param ITempImageRepository $tempImage
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadTempImage(Request $request, ITempImageRepository $tempImage)
    {

        try {

            /**
             * @var $file UploadedFile
             */
            $file = $request->file('file');

            // Save image.
            $tempImage->saveImage($file, 'person');

            $this->data['tempImage'] = $tempImage->getTempImage();

        } catch (\Exception $ex) {
            return ServiceResponse::jsonNotification($ex->getMessage(), $ex->getCode(), []);
        }

        return ServiceResponse::jsonNotification(__('Upload image successfully'), 200,  $this->data );
    }



}
