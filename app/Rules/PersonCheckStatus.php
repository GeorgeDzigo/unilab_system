<?php

namespace App\Rules;

use App\Models\Person;
use Illuminate\Contracts\Validation\Rule;

class PersonCheckStatus implements Rule
{

    /**
     * @var
     */
    protected $text = '';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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

        /**
         * @var $person Person
         */
        $person = Person::where('personal_number', $value)->first();

        if (is_null($person)) {
            $this->text = 'აღნიშნული პირი არ მოიძებნა';
            return false;
        }

        if (!$person->status) {
            $this->text = 'აღნიშნული პირი არ არის აქტიური';
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
