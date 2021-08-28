<?php


namespace App\Http\Controllers\Admin;


use App\Http\Requests\ItemLog\SaveLogRequest;
use App\Models\Department;
use App\Models\Item;
use App\Models\ItemLog;
use App\Models\ItemLogPositionPerson;
use App\Models\PeoplePosition;
use App\Models\Person;
use App\Models\Position;
use App\Repositories\Contracts\IItemLogRepository;
use App\User;
use App\Utilities\ServiceResponse;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ItemLogController extends CrudController
{

    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;

    public function setup()
    {

        $this->crud->setModel(ItemLog::class);
        $this->crud->setRoute( config('backpack.base.route_prefix') . '/log-item');
        $this->crud->setEntityNameStrings(__('მოწყობილობის გატარება'), __('გაცემა/მიღება'));

        $this->filter();
    }


    /**
     * Filter data.
     */
    public function filter()
    {
        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'filter_item_name',
            'label' => __('მოწყობილობა')
        ],
        false,
        function ($value) {

            $item = Item::where('name', 'LIKE', '%'.$value.'%')->pluck('id')->toArray();

            $this->crud->addClause('whereIn', 'item_id', $item);
        });

        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'filter_person_name',
            'label' => __('პირი')
        ],
        false,
        function ($value) {

            $item = Person::orWhere('first_name', 'LIKE', '%'.$value.'%')
                    ->orWhere('last_name', 'LIKE', '%'.$value.'%')->pluck('id')->toArray();

            $this->crud->addClause('whereIn', 'person_id', $item);
        });

        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'filter_user',
            'label' => __('გამტარებელი')
        ],
        false,
        function ($value) {

            $item = User::where('name', 'LIKE', '%'.$value.'%')->pluck('id')->toArray();

            $this->crud->addClause('whereIn', 'user_id', $item);
        });

        $this->crud->addFilter([
            'type' => 'select2_multiple',
            'name' => 'filter_status',
            'label' => __('ქმედება')
        ],
        function(){
            return [
                1   => __('გაცემა'),
                2   => __('მიღება')
            ];
        },
        function ($value) {
            $values = json_decode($value, true);

            $this->crud->addClause('whereIn', 'action', $values);
        });


        $this->crud->addFilter([
            'type' => 'select2_multiple',
            'name' => 'filter_positions',
            'label' => __('პოზიცია')
        ],
        function(){
            return Position::getAllKeyValue();
        },
        function ($value) {

            $values = json_decode($value, true);

            $peopleId = PeoplePosition::whereIn('position_id', $values)->pluck('id')->toArray();

            $itemLogId = ItemLogPositionPerson::whereIn('position_person_id', $peopleId)->pluck('item_log_id')->toArray();

            $this->crud->addClause('whereIn', 'id', $itemLogId);
        });

        $this->crud->addFilter([
            'type' => 'select2_multiple',
            'name' => 'filter_department',
            'label' => __('მიმართულება')
        ],
            function(){
                return Department::getAllKeyValue();
            },
            function ($value) {

                $values = json_decode($value, true);

                $peopleId = PeoplePosition::whereIn('department_id', $values)->pluck('id')->toArray();

                $itemLogId = ItemLogPositionPerson::whereIn('position_person_id', $peopleId)->pluck('item_log_id')->toArray();

                $this->crud->addClause('whereIn', 'id', $itemLogId);
            });

        $this->crud->addFilter([
            'type' => 'date_range',
            'name' => 'filter_created_at',
            'label' => __('გატარების თარიღი')
        ],
            false,
        function ($value) { // if the filter is active, apply these constraints
            $dates = json_decode($value);
            $this->crud->addClause('where', 'created_at', '>=', Carbon::parse($dates->from));
            $this->crud->addClause('where', 'created_at', '<=', Carbon::parse($dates->to)->subSeconds(59)->addDays(1) );
        });

    }

    /**
     * Display all rows in the database for this entity.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->crud->hasAccessOrFail('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = 'გაცემა/მიღება';

        return view('item_log.list', $this->data);
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

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('items.manage', $this->data);
    }

    /**
     * List
     */
    protected function setupListOperation()
    {

        $this->crud->addColumn([
                'name' => 'action',
                'type' => 'model_function',
                'function_name' => 'getActionName',
                'label' => __('ქმედება'),
                'limit' => 10000
            ]
        );

        $this->crud->addColumn([
                'name' => 'person',
                'type' => 'model_function',
                'function_name' => 'getPersonName',
                'label' => __('პირი'),
                'limit' => 10000
            ]
        );

        $this->crud->addColumn([
                'name' => 'item',
                'type' => 'model_function',
                'function_name' => 'getItemName',
                'label' => __('მოწყობილობა'),
                'limit' => 10000
            ]
        );

        $this->crud->addColumn([
                'name' => 'type',
                'type' => 'model_function',
                'function_name' => 'getUserName',
                'label' => __('გამტარებელი'),
                'limit' => 10000
            ]
        );

        $this->crud->addColumn([
                'name' => 'created_at',
                'type' => 'text',
                'label' => __('გატარების თარიღი')
            ]
        );

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkItem(Request $request)
    {
        try {

            /**
             * @var $item Item
             */
            $item = Item::find($request->get('id'));

            if (is_null($item)) {
                throw new \Exception('მოწყობილობა არ მოიძებნა', 500);
            }

            if ( !$item->status ) {
                throw new \Exception('მოწყობილობა არ არის აქტიური!', 500);
            }

            $data['item'] = $item->load('type');

        } catch (\Exception $ex) {
            return ServiceResponse::jsonNotification($ex->getMessage(), $ex->getCode(), []);
        }

        return ServiceResponse::jsonNotification(__('მოწყობილობა წარმატებით მოიძებნა'), 200, $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPerson(Request $request)
    {
        try {

            $person= null;

            if ( $request->get('personal_number') ) {

                /**
                 * @var $person Person
                 */
                $person = Person::where('personal_number',$request->get('personal_number'))->first();

            } else if($request->get('card_id')){
                /**
                 * @var $person Person
                 */
                $person = Person::where('card_id',$request->get('card_id'))->first();
            }

            if (is_null($person)) {
                throw new \Exception('Person not found', 500);
            }

            if ( !$person->status ) {
                throw new \Exception('აღნიშნული პირი არ არის აქტიური!', 500);
            }

            $data['person'] = $person;

        } catch (\Exception $ex) {
            return ServiceResponse::jsonNotification($ex->getMessage(), $ex->getCode(), []);
        }

        return ServiceResponse::jsonNotification(__('პირი წარმატებით მოიძებნა!'), 200, $data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData()
    {

        try {

            $data['routes'] = [
                'checkItemRoute'        => route('item_log.check_item'),
                'checkPersonRoute'      => route('item_log.check_person'),
                'saveLogRoute'          => route('item_log.save'),
                'scanner'              => route('scan')
            ];

        } catch (\Exception $ex) {
            return ServiceResponse::jsonNotification($ex->getMessage(), $ex->getCode(), []);
        }

        return ServiceResponse::jsonNotification(__('პირი წარმატებით მოიძებნა!'), 200, $data);
    }

    /**
     * @param SaveLogRequest $request
     * @param IItemLogRepository $itemLogRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveLog(SaveLogRequest $request, IItemLogRepository $itemLogRepository)
    {
        try {

            // Save item log.
            $itemLogRepository->saveLog($request);

            $data = [];

        } catch (\Exception $ex) {
            return ServiceResponse::jsonNotification($ex->getMessage(), $ex->getCode(), []);
        }

        return ServiceResponse::jsonNotification(__('ნივთი წარმატებით გატარდა!'), 200, $data);
    }

}
