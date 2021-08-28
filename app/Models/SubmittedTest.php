<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmittedTest extends Model
{
    protected $table = 'submitted_tests';

    protected $fillable = [
        'department_id',
        'test_id',
        'competition_id',
        'first_name',
        'last_name',
        'email',
        'address',
        'work_experience',
        'birth_date',
    ];


    public function answers() {
        return $this->hasMany(SubmittedTestAnswers::class, 'submitted_test_id')->orderBy('id', 'ASC');
    }
    public function department() {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function test() {
        return $this->belongsTo(Test::class, 'test_id');
    }

    public function competition() {
        return $this->belongsTo(Competition::class, 'competition_id');
    }
}
