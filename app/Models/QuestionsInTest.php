<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class QuestionsInTest extends Model {

  use CrudTrait;

  /*
  |--------------------------------------------------------------------------
  | GLOBAL VARIABLES
  |--------------------------------------------------------------------------
  */

  protected $table = 'questions_in_test';

  protected $fillable = ['test_id'];

  public function question()
  {
      return $this->belongsTo(TestQuestions::class, 'question_id', 'id');
  }
}
