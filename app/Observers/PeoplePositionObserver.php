<?php

namespace App\Observers;

use App\Models\PeoplePosition;
use App\Repositories\Contracts\IPersonRepository;
use Carbon\Carbon;

/**
 * @property IPersonRepository personRepository
 */
class PeoplePositionObserver
{

    public function __construct
    (
        IPersonRepository $personRepository
    )
    {
        $this->personRepository = $personRepository;
    }

    /**
     * Handle the people position "updated" event.
     *
     * @param  \App\Models\PeoplePosition  $peoplePosition
     * @return void
     */
    public function updated(PeoplePosition $peoplePosition)
    {

        /**
         * Set person.
         */
        $this->personRepository->setPerson($peoplePosition->person);

        /**
         * Modify person status.
         */
        $this->personRepository->modifyPersonStatus();

    }

    /**
     * Handle the people position "deleted" event.
     *
     * @param  \App\Models\PeoplePosition  $peoplePosition
     * @return void
     */
    public function deleted(PeoplePosition $peoplePosition)
    {

        /**
         * Set person.
         */
        $this->personRepository->setPerson($peoplePosition->person);

        /**
         * Modify person status.
         */
        $this->personRepository->modifyPersonStatus();

    }


}
