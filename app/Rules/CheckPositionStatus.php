<?php

namespace App\Rules;

use App\Models\Person;
use App\Repositories\Contracts\IPersonRepository;
use Illuminate\Contracts\Validation\Rule;

/**
 * @property IPersonRepository personRepository
 */
class CheckPositionStatus implements \Illuminate\Contracts\Validation\ImplicitRule
{

    /**
     * @var string
     */
    private $text = '';

    /**
     * @var
     */
    protected $params;

    /**
     * @var IPersonRepository
     */
    protected $personRepository;

    /**
     * Create a new rule instance.
     *
     * @param array $params
     * @param IPersonRepository $personRepository
     */
    public function __construct(array $params, IPersonRepository $personRepository)
    {
        $this->params = $params;
        $this->personRepository = $personRepository;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        try {

            /**
             * @var $person Person
             */
            $person = Person::where('personal_number', $this->params['person_id'])->first();

            if (is_null($person)) {
                $this->text = 'აღნიშნული პირი არ მოიძებნა';
                return false;
            }

            // Set person.
            $this->personRepository->setPerson($person);

            // Check person position status.
            $positionsStatus = $this->personRepository->checkPersonPositionStatus();

            if (!$positionsStatus) {
                $this->text = 'აღნიშნული პირის პოზიცია, არ არის აქტიური!';
                return false;
            }

        } catch (\Exception $ex) {
            $this->text = $ex->getMessage();
            return false;
        }


        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->text;
    }
}
