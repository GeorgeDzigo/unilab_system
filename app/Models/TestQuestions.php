<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Support\Facades\Storage;

class TestQuestions extends Model {

  use CrudTrait;

  /*
  |--------------------------------------------------------------------------
  | GLOBAL VARIABLES
  |--------------------------------------------------------------------------
  */

  protected $table = 'test_questions';

  protected $fillable = [
        'question_title',
        'question_title_explanation',
        'question_file',
        'question_image',
        'question_image_explanation',
        'option_type',
        'is_optional',
        'option_type_key',
        'option_single_select_checkbox',
        'option_multi_select_checkbox'
    ];



    public function options() {
        return $this->hasMany(TestQuestionsAnswers::class, 'question_id');
    }
}
