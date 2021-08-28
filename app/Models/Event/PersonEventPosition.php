<?php

namespace App\Models\Event;

use App\Models\Department;
use App\Models\Person;
use App\Models\Position;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonEventPosition extends BaseEventModel
{

    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'person_event_positions';

    /**
     * @var array
     */
    protected $fillable = [
        'event_log_id',
        'person_id',
        'position_id',
        'department_id',
        'biostar_event_id'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function eventLog()
    {
        return $this->belongsTo(EventLog::class, 'event_log_id', 'id')->withoutGlobalScopes();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id', 'id')->withoutGlobalScopes();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id')->withoutGlobalScopes();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id')->withoutGlobalScopes();
    }
}
