<?php


namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Item\CreateRequest;
use App\Models\Department;
use App\Models\Item;
use App\Models\ItemType;
use App\Models\PeoplePosition;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Carbon\Carbon;

class ItemController extends CrudController
{

    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(Item::class);
        $this->crud->setRoute( config('backpack.base.route_prefix') . '/item');
        $this->crud->setEntityNameStrings('მოწყობილობა', 'მოწყობილობები');
        $this->crud->addClause('orderBy', 'id', 'desc');

        $this->setupCreateDefaults();

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
                $this->crud->addClause('where', 'name', 'like', '%' . $value . '%');
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
            'name' => 'filter_type',
            'label' => __('ტიპი')
        ],
            function(){
                return ItemType::getAllKeyValue();
            },
            function ($value) {

                $values = json_decode($value, true);

                $itemId = ItemType::whereIn('id', $values)->pluck('id')->toArray();

                $this->crud->addClause('whereIn', 'id', $itemId);
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

    protected function setupListOperation()
    {

        $this->crud->addColumn([
                'name' => 'id',
                'type' => 'text',
                'label' => __('იდენტიფიკატორი')
            ]
        );

        $this->crud->addColumn([
                'name' => 'name',
                'type' => 'text',
                'label' => __('სახელი')
            ]
        );

        $this->crud->addColumn([
                'name' => 'type',
                'type' => 'model_function',
                'function_name' => 'getTypeName',
                'label' => __('ტიპი'),
                'limit' => 10000
            ]
        );

        $this->crud->addColumn([
                'name' => 'qrSrc',
                'type' => 'image',
                 'label'    => 'Qr',
                 'height' => '60px',
                 'width' => '60px',
            ]
        );

        $this->crud->addColumn([
            'name' => 'status',
            'label' => __('სტატუსი'),
            'type' => 'model_function',
            'function_name' => 'getStatusColumn',
            'limit' => 1009,
        ]);

        $this->crud->addColumn([
                'name' => 'created_at',
                'type' => 'date',
                'label' => __('შექმნის თარიღი')
            ]
        );

    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(CreateRequest::class);

        $this->crud->addField([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Name'
        ]);
//
//        $this->crud->addField([
//            'name' => 'number',
//            'type' => 'text',
//            'label' => 'Number'
//        ]);

        $this->crud->addField([
            'label' => 'Item type',
            'type' => 'select',
            'name' => 'type_id',
            'entity' => 'type',
            'attribute' => 'name',

            // optional
            'model' => 'App\Models\ItemType',
            'options'   => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            }),
        ]);

        $this->crud->addField([
            'name' => 'attributes',
            'type' => 'table',
            'label' => __('All Attributes'),
            'entity_singular' => 'option',
            'columns' => [
                'name' => __('Name'),
                'value' => __('Value')
            ],
            'min' => 0,
        ]);

        $this->crud->addField([
            'name' => 'status',
            'type' => 'checkbox',
            'label' => 'Status'
        ]);


    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

}
