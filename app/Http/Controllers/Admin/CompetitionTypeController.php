<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Models\CompetitionType;

class CompetitionTypeController extends CrudController {

  use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

  public function setup() 
  {
      $this->crud->setModel(CompetitionType::class);
      $this->crud->setRoute(config('backpack.base.route_prefix') . '/competitiontype');
      $this->crud->setEntityNameStrings('Competition Type', 'Competition Type');
  }

  protected function setupListOperation()
  {
      // TODO: remove setFromDb() and manually define Columns, maybe Filters
    
    $this->crud->setFromDb();
      

  }

  protected function setupCreateOperation()
  {
      $this->crud->setValidation(\App\Http\Requests\Admin\CompetitionType\CompetitionTypeRequest::class);

      // TODO: remove setFromDb() and manually define Fields
      $this->crud->setFromDb();
  }

  protected function setupUpdateOperation()
  {
      $this->setupCreateOperation();
  }
}