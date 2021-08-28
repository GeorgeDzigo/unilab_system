<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Requests\Admin\Competition\CompetitionRequest;
use App\Models\Competition;
use App\Models\Department;

class CompetitionController extends CrudController {
      
  use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

  public function setup() 
  {
      $this->crud->setModel(Competition::class);
      $this->crud->setRoute(config('backpack.base.route_prefix') . '/competition');
      $this->crud->setEntityNameStrings('competition', 'competitions');
  }

  protected function setupListOperation()
  {
      // TODO: remove setFromDb() and manually define Columns, maybe Filters
     // dd('aq var'); 
      $this->crud->setFromDb();
      $this->crud->addColumn([
        'name'      => 'competitiontype.name', // name of relationship method in the model
        'type'      => 'relationship_count', 
        'label'     => 'Competition type name', // Table column heading
       // 'suffix'    => ' competitionType', // to show "123 tags" instead of "123"
     ]);
       
       

     $this->crud->removeColumn('competition_type_id');

  }

  protected function setupCreateOperation()
  {
      $this->crud->setValidation(CompetitionRequest::class);

      // TODO: remove setFromDb() and manually define Fields
      $this->crud->setFromDb();

      $this->crud->addField([
       'label' => 'Department',
       'type' => 'select2_multiple',
       'name' => 'department',
       'entity' => 'department',
       'attribute' => 'name',

       'pivot' => true,

       'model' => Department::class,
       'options' => (function ($query) {
          return $query->orderBy('name', 'ASC')->get();
       })

    ]); 


  //   $this->crud->addField([
  //     'type' => 'select2_multiple',
  //     'name' => 'department', // the relationship name in your Model
  //     'entity' => 'department', // the relationship name in your Model
  //     'attribute' => 'departments', // attribute on Article that is shown to admin
  //     'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
  // ]); 
   
    $this->crud->addField(
    [  // competition type 
      'label'     => 'Competition type name',
      'type'      => 'select2',
      'name'      => 'Competition_type_name', // the db column for the foreign key
  
      
      'entity'    => 'competitionType', // the method that defines the relationship in your Model
      'model'     => "App\Models\CompetitionType", // foreign key model
      'attribute' => 'name', // foreign key attribute that is shown to user
      'default'   => 2, // set the default value of the select2
  
      
      'options'   => (function ($query) {
          return $query->orderBy('name', 'ASC')->get();
      }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select ->where('depth', 1)
  ]);



   $this->crud->removeField('competition_type_id');
     

  }

  protected function setupUpdateOperation()
  {
      $this->setupCreateOperation();
  }
}