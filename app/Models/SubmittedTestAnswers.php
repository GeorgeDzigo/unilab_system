<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmittedTestAnswers extends Model
{
    protected $table = 'submitted_tests_answers';

    protected $fillable = [
        'submitted_test_id',
        'question_id',
        'question_name',
        'text_answer',
        'text_answer',
        'selected_answer',
    ];

    public function question()
    {
        return $this->belongsTo(TestQuestions::class, 'question_id');
    }
}
