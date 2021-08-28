<?php

namespace App\Listeners\Person;

use App\Events\Person\DisablePersonEvent;
use App\Models\Person;
use App\Services\Events\Contracts\IChangeBiostarUserStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * @property IChangeBiostarUserStatus changeBiostarUserStatus
 */
class DisablePersonInBiostar
{

    /**
     * @var IChangeBiostarUserStatus
     */
    protected $changeBiostarUserStatus;

    /**
     * Create the event listener.
     *
     * @param IChangeBiostarUserStatus $changeBiostarUserStatus
     */
    public function __construct(
        IChangeBiostarUserStatus $changeBiostarUserStatus
    )
    {
        $this->changeBiostarUserStatus = $changeBiostarUserStatus;
    }

    /**
     * Handle the event.
     *
     * @param  DisablePersonEvent  $event
     * @return void
     */
    public function handle(DisablePersonEvent $event)
    {
        if ($event->person->status == Person::DISABLE_STATUS && $event->oldStatus == Person::ENABLE_STATUS) {
            $this->changeBiostarUserStatus->setPerson($event->person)
                                    ->setStatus($event->person->status)
                                        ->changeStatus();
        }

    }

}
