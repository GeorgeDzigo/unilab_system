<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Competition extends Model
{
    //
    use CrudTrait;


    /*
  |--------------------------------------------------------------------------
  | GLOBAL VARIABLES
  |--------------------------------------------------------------------------
  */

  protected $table = 'competitions';
  // protected $primaryKey = 'id';
  // public $timestamps = false;
  protected $guarded = ['id'];
   protected $fillable = ['title','competition_type_id', 'start_date', 'end_date'  ];
  // protected $hidden = [];
  // protected $dates = [];

  /*
  |--------------------------------------------------------------------------
  | FUNCTIONS
  |--------------------------------------------------------------------------
  */

  /*
  |--------------------------------------------------------------------------
  | RELATIONS
  |--------------------------------------------------------------------------
  */
  public function competitionType()
  {
      return $this->belongsTo(CompetitionType::class);
  }

  public function department()
  {
      return $this->belongsToMany(Department::class);
  }



  /*
  |------------------------------------ -- ------------------------------------
  | SCOPES
  |--------------------------------------------------------------------------
  */

  /*
  |--------------------------------------------------------------------------
  | ACCESSORS
  |--------------------------------------------------------------------------
  */

  /*
  |--------------------------------------------------------------------------
  | MUTATORS
  |--------------------------------------------------------------------------
  */

}
