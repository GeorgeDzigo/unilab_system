<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Position\CreateRequest;
use App\Models\Department;
use App\Models\Position;
use App\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;

class DepartmentController extends CrudController
{

    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(Department::class);
        $this->crud->setRoute( config('backpack.base.route_prefix') . '/department');
        $this->crud->setEntityNameStrings('მიმართულება', 'მიმართულებები');

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

    }

    protected function setupListOperation()
    {
        $this->crud->addColumn([
                'name' => 'name',
                'type' => 'text',
                'label' => 'Name']
        );

        $this->crud->addColumn([
            'name' => 'status',
            'label' => 'Status',
            'type' => 'model_function',
            'function_name' => 'getStatusColumn',
            'limit' => 1009,
        ]);

        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => __('შექმნის თარიღი'),
            'type' => 'date'
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(CreateRequest::class);

        $this->crud->addField([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Name'
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
