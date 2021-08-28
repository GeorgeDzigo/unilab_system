<?php


namespace App\Services\Events\Objects;


use App\Models\BioStar\TbEventLog;
use App\Models\BioStar\TbUser;
use App\Models\BioStar\TbUserCard;
use App\Models\Event\EventLog;
use App\Models\Event\PersonEventInfo;
use App\Models\Event\PersonEventPosition;
use App\Models\Person;
use App\Repositories\Eloquent\EventLogRepository;
use App\Services\Events\Contracts\ISaveEventLogService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

/**
 * @property Collection biostarEventLogData
 * @property Person person
 * @property EventLog eventLog
 * @property |null cardId
 * @property  biostarUserId
 */
class SaveEventLogService implements ISaveEventLogService
{

    /**
     * @var Collection
     */
    protected $biostarEventLogData;

    /**
     * @var TbEventLog
     */
    protected $biostarData;

    /**
     * @var Person
     */
    protected $localPerson;

    /**
     * @var EventLog
     */
    protected $eventLog;

    /**
     * @var
     */
    protected $cardId;

    /**
     * @var
     */
    protected $biostarUserId;

    /**
     * @param Collection $eventLogData
     * @return $this
     */
    public function setBiostarEventLogData(Collection $eventLogData)
    {
        $this->biostarEventLogData = $eventLogData;
        return $this;
    }

    /**
     *
     * Parse and save event log data.
     *
     * @return mixed|void
     */
    public function parseAndSaveData()
    {
        foreach($this->biostarEventLogData->chunk(1000) as $biostarDataChunk) {
            /**
             * @var $biostarData TbEventLog
             */
            foreach($biostarDataChunk as $biostarData) {
                $this->biostarData = $biostarData;

                $bioStarUser = TbUser::where('sUserID', $this->biostarData->nUserID)->first();
                $cardId = null;
                if ($bioStarUser) {
                    $this->biostarUserId = $bioStarUser->nUserIdn;
                    $tbUserCard = TbUserCard::where('nUserIdn', $this->biostarUserId)->where('nLatest', 1)->first();
                    $cardId = $tbUserCard->sCardNo;
                }
                $this->cardId = $cardId;

                $this->setPerson();

                $this->saveEventLog();

                $this->savePersonEventInfo();

                $this->savePeronEventPosition();
            }
        }

    }

    /**
     * Save Person event positions.
     */
    private function savePeronEventPosition()
    {
        if (!$this->localPerson) {
            return;
        }

        foreach($this->localPerson->activePositions as $personPosition) {
            PersonEventPosition::updateOrCreate([
                'biostar_event_id'  => $this->biostarData->nEventLogIdn,
                'position_id'       => $personPosition->position_id,
                'department_id'     => $personPosition->department_id,
                'person_id'         => $this->localPerson->id
            ],[
                'event_log_id'      => $this->eventLog->id,
            ]);
        }
    }

    /**
     * Save Person event info.
     */
    private function savePersonEventInfo()
    {
        if (!$this->localPerson) {
            return;
        }

        Log::info('log', [
            'log'   => $this->biostarData
        ]);

        PersonEventInfo::updateOrCreate([
            'biostar_event_id'  => $this->biostarData->nEventLogIdn
        ],[
            'person_id'             => $this->localPerson->id,
            'personal_number'       => $this->localPerson->personal_number,
            'card_id'               => $this->localPerson->card_id,
            'biostar_card_id'       => $this->localPerson->biostar_card_id,
            'additional_info'       => $this->localPerson->additional_info ?: [],
            'gender'                => $this->localPerson->gender,
            'birth_date'            => $this->localPerson->birth_date,
            'unilab_email'          => $this->localPerson->unilab_email,
            'personal_email'        => $this->localPerson->personal_email,
            'status'                => $this->localPerson->status,
            'event_log_id'          => $this->eventLog->id
        ]);
    }

    /**
     * Set Person.
     */
    private function setPerson()
    {
        /** @var $person Person */
        $this->localPerson = Person::where('biostar_card_id', $this->cardId)->first();
    }

    /**
     * Save Event log data.
     */
    private function saveEventLog()
    {
        $eventType = null;
        if ($this->biostarData->nReaderIdn == '544381877') {
            $eventType = EventLog::EVENT_TYPE_ENTRANCE_SAN_DIEGO;
        } else if ($this->biostarData->nReaderIdn == '544381878') {
            $eventType = EventLog::EVENT_TYPE_EXIT;
        } else if ($this->biostarData->nReaderIdn == '544381879') {
            $eventType = EventLog::EVENT_TYPE_ENTRANCE;
        }

        $this->eventLog = EventLog::updateOrCreate([
            'biostar_event_id'  => $this->biostarData->nEventLogIdn
        ],[
            'event_date'        => Carbon::createFromTimestamp($this->biostarData->nDateTime)->subHours(4),
            'person_id'         => $this->localPerson ? $this->localPerson->id : '',
            'biostar_card_id'   => $this->cardId,
            'biostar_reader_id' => $this->biostarData->nReaderIdn,
            'event_type'        => $eventType,
            'biostar_user_id'   => $this->biostarUserId
        ]);
    }
}
