<?php

namespace App\Console\Commands;

use App\Models\Item;
use App\Models\PeoplePosition;
use App\Models\Person;
use App\Repositories\Contracts\IPeoplePositionRepository;
use App\Repositories\Contracts\IPersonRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property IPersonRepository personRepository
 * @property IPeoplePositionRepository peoplePositionRepository
 */
class CheckPersonPositionStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'person:checkPositionStatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check person position status';

    /**
     * @var IPersonRepository
     */
    protected $personRepository;

    /**
     * @var IPeoplePositionRepository
     */
    protected $peoplePositionRepository;

    /**
     * Create a new command instance.
     *
     * @param IPeoplePositionRepository $peoplePositionRepository
     * @param IPersonRepository $personRepository
     */
    public function __construct
    (
        IPeoplePositionRepository $peoplePositionRepository,
        IPersonRepository $personRepository
    )
    {
        parent::__construct();
        $this->personRepository = $personRepository;
        $this->peoplePositionRepository = $peoplePositionRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        /**
         * @var $items Collection
         */
        Person::latest()->chunk(500, function($items){

            /**
             * @var $person Person
             */
            foreach($items as $person) {

                /**
                 * Set Person.
                 */
                $this->personRepository->setPerson($person);

                /**
                 * @var $personPosition PeoplePosition
                 */
                foreach($person->activePositions as $personPosition ) {

                    /**
                     * Set People position.
                     */
                    $this->peoplePositionRepository->setPeoplePosition($personPosition);

                    /**
                     * Disable/Enable people position active status.
                     */
                    $this->peoplePositionRepository->checkDateAndChangeActive();

                }

                // If person has not a active person positions, disable this person
                $this->personRepository->modifyPersonStatus();

            }

        });


    }


}
