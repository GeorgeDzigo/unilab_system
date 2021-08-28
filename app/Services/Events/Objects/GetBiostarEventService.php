<?php

namespace App\Services\Events\Objects;

use App\Models\BioStar\TbEventLog;
use App\Models\BioStar\TbEventLogBk;
use App\Services\Events\Contracts\IGetBiostarEventService;
use Carbon\Carbon;

/**
 * @property integer startDate
 * @property integer endDate
 * @property TbEventLog tbEventLog
 * @property mixed userId
 * @property mixed readerId
 */
class GetBiostarEventService implements IGetBiostarEventService
{

    /**
     * @var integer
     */
    protected $startDate;

    /**
     * @var integer
     */
    protected $endDate;

    /**
     * @var TbEventLog
     */
    protected $tbEventLog;

    /**
     * @var
     */
    protected $userId;

    /**
     * @var
     */
    protected $readerId;

    /**
     * @var
     */
    protected $eventLogsData;

    /**
     * GetBiostarEventService constructor.
     * @param TbEventLog $tbEventLog
     */
    public function __construct(
        TbEventLog $tbEventLog
    )
    {
        $this->tbEventLog = $tbEventLog;
    }

    /**
     * @param $date
     * @return $this
     */
    public function setStartDate(Carbon $date)
    {
        $this->startDate = $date->timestamp;
        return $this;
    }

    /**
     * @param $date
     * @return $this
     */
    public function setEndDate(Carbon $date)
    {
        $this->endDate = $date->timestamp;
        return $this;
    }

    /**
     * @param $userId
     * @return $this
     */
    public function setUser($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @param $readerId
     * @return $this
     */
    public function setReaderId($readerId)
    {
        $this->readerId = $readerId;
        return $this;
    }

    /**
     * @return $this|mixed
     */
    public function setEventLogsData()
    {
        /**
         * @var $eventLogs
         */
//        $eventLogs = $this->tbEventLog->whereBetween('nDateTime', [$this->startDate, $this->endDate]);
        $eventLogs = $this->tbEventLog->query();

        if ($this->userId) {
            $eventLogs = $eventLogs->where('nUserID', $this->userId);
        }

        if ($this->readerId) {
            $eventLogs = $eventLogs->where('nReaderIdn', $this->readerId);
        }

        $eventLogs = $eventLogs->whereIn('nEventIdn',[47]);

        $this->eventLogsData = $eventLogs;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEventLogsData()
    {
        return $this->eventLogsData->get();
    }

}
