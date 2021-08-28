<?php

namespace App\Models\Event;

use App\Models\Person;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed event_type
 * @property mixed id
 */
class EventLog extends BaseEventModel
{

    use SoftDeletes, CrudTrait;

    const EVENT_TYPE_ENTRANCE = 1;
    const EVENT_TYPE_EXIT = 2;
    const EVENT_TYPE_ENTRANCE_SAN_DIEGO = 3;

    /**
     * @var string
     */
    protected $table = 'event_logs';

    /**
     * @var array
     */
    protected $fillable = [
        'event_date',
        'person_id',
        'biostar_card_id',
        'biostar_event_id',
        'biostar_reader_id',
        'event_type',
        'biostar_user_id'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'deleted_at',
        'event_date'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reader()
    {
        return $this->belongsTo(Reader::class, 'biostar_reader_id', 'biostar_reader_id');
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
    public function localPersonRel()
    {
        return $this->belongsTo(Person::class, 'person_id', 'id')->withoutGlobalScopes();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function personInfo()
    {
        return $this->hasOne(PersonEventInfo::class, 'event_log_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function personPositions()
    {
        return $this->hasMany(PersonEventPosition::class, 'event_log_id', 'id');
    }

    /**
     * @return query
     */
    public function scopePersonFilter($query, $person_id)
    {
        return $query->where('person_id',$person_id);
    }

    /**
     * @return query
     */
    public function scopeSearchByDate($query, $from, $till)
    {
        return $query->where('event_logs.event_date', '>=', $from)->where('event_logs.event_date', '<=', date('Y-m-d',strtotime($till) + 86400));
    }

}
