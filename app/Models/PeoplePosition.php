<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed end_date
 * @property mixed person
 */
class PeoplePosition extends BaseModel
{

    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'positions_people';

    /**
     * @var array
     */
    protected $fillable = [
        'people_id',
        'position_id',
        'start_date',
        'end_date',
        'active',
        'doc_number',
        'department_id'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'start_date',
        'end_date',
        'deleted_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person()
    {
        return $this->belongsTo(Person::class, 'people_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    /**
     * @return array
     */
    public function positionPeople($group)
    {
        return $this->whereHas('position',function($qvery) use($group)
            {
                $qvery->whereIn('position_id',$group);
            })
            ->get('people_id')
            ->pluck('people_id')
            ->toArray();
    }

    /**
     * @return array
     */
    public function departamentPeople($group)
    {
        return $this->whereHas('department',function($qvery) use($group)
            {
                $qvery->whereIn('department_id',$group);
            })
            ->get('people_id')
            ->pluck('people_id')
            ->toArray();
    }

}
