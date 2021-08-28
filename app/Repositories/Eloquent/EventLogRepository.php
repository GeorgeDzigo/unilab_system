<?php


namespace App\Repositories\Eloquent;


use App\Models\Event\EventLog;
use App\Repositories\Contracts\IEventLogRepository;
use Illuminate\Database\Eloquent\Model;

class EventLogRepository extends BaseRepository implements IEventLogRepository
{

    /**
     * EventLogRepository constructor.
     * @param EventLog $model
     */
    public function __construct(EventLog $model)
    {
        parent::__construct($model);
    }



}
