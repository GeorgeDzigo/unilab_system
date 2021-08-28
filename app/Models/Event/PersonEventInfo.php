<?php

namespace App\Models\Event;

use App\Models\Person;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonEventInfo extends BaseEventModel
{

    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'person_event_infos';

    /**
     * @var array
     */
    protected $fillable = [
        'event_log_id',
        'person_id',
        'personal_number',
        'card_id',
        'biostar_card_id',
        'additional_info',
        'gender',
        'birth_date',
        'unilab_email',
        'personal_email',
        'status',
        'biostar_event_id'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'deleted_at',
        'birth_date'
    ];

    protected $casts = [
        'additional_info'   => 'json'
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

}
