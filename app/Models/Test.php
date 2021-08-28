<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Test extends Model {

  use CrudTrait;

  /*
  |--------------------------------------------------------------------------
  | GLOBAL VARIABLES
  |--------------------------------------------------------------------------
  */

  protected $table = 'tests';

  protected $fillable = ['title'];

  public function questions()
  {
        return $this->hasMany(QuestionsInTest::class, 'test_id')->orderBy('sort');
  }
}
