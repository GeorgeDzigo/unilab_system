<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetitionDepartment extends Model
{
    protected $table = 'competition_department';

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function competition() {
        return $this->belongsTo(Competition::class, 'competition_id');
    }
}
