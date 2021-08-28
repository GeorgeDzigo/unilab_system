<?php


namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ItemType\CreateRequest;
use App\Models\ItemType;
use App\Models\Position;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class ItemTypeController extends CrudController
{

    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(ItemType::class);
        $this->crud->setRoute( config('backpack.base.route_prefix') . '/item-type');
        $this->crud->setEntityNameStrings('მოწყობილობის ტიპი', 'მოწყობილობის ტიპები');

    }

    protected function setupListOperation()
    {
        $this->crud->addColumn([
                'name' => 'name',
                'type' => 'text',
                'label' => 'Name']
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


    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

}
