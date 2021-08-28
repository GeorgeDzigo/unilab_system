<?php

namespace App\Services\Events\Contracts;

use Carbon\Carbon;

interface IGetBiostarEventService
{

    /**
     * @param Carbon $date
     * @return mixed
     */
    public function setStartDate(Carbon $date);

    /**
     * @param Carbon $date
     * @return mixed
     */
    public function setEndDate(Carbon $date);

    /**
     * @param $userId
     * @return mixed
     */
    public function setUser($userId);

    /**
     * @param $readerId
     * @return mixed
     */
    public function setReaderId($readerId);

    /**
     * @return mixed
     */
    public function setEventLogsData();

    /**
     * @return mixed
     */
    public function getEventLogsData();

}
