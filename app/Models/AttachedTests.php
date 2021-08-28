<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class AttachedTests extends Model
{
    //
    use CrudTrait;


    /*
  |--------------------------------------------------------------------------
  | GLOBAL VARIABLES
  |--------------------------------------------------------------------------
  */

    protected $table = 'competitions_attached_tests';

    protected $fillable = ['title','competition_id', 'test_id',];

    public function test()
    {
        return $this->belongsTo(Test::class, 'test_id');
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class, 'competition_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }


}
