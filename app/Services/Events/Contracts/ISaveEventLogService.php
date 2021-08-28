<?php


namespace App\Services\Events\Contracts;


use Illuminate\Database\Eloquent\Collection;

interface ISaveEventLogService
{

    /**
     * @param Collection $eventLogData
     * @return mixed
     */
    public function setBiostarEventLogData(Collection $eventLogData);

    /**
     * @return mixed
     */
    public function parseAndSaveData();

}
