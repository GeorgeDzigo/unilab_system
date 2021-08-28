<?php


namespace App\Http\Controllers\Admin;


use App\Models\Event\EventLog;
use App\Models\Event\EventLogCrud;
use App\Models\ItemLog;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class EventController extends CrudController
{

    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;

    /**
     * @throws \Exception
     */
    public function setup()
    {
        $this->crud->setModel(EventLogCrud::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/events');
        $this->crud->setEntityNameStrings(__('შემოსვლა/გასვლა'), __('შემოსვლა/გასვლა'));
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

//    $this->crud->addColumn([
//            'name' => 'item',
//            'type' => 'model_function',
//            'function_name' => 'getItemName',
//            'label' => __('წამკითხველი'),
//            'limit' => 10000
//        ]
//    );

        $this->crud->addColumn([
                'name' => 'event_date',
                'type' => 'text',
                'label' => __('თარიღი')
            ]
        );
    }
}
