<?php

namespace App\Rules;

use App\Models\Item;
use App\Models\ItemLog;
use App\Models\Person;
use Illuminate\Contracts\Validation\Rule;

/**
 * @property array params
 */
class ItemCheckAction implements Rule
{

    /**
     * @var
     */
    protected $params;

    /**
     * @var
     */
    protected $text = '';

    /**
     * Create a new rule instance.
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
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

        if (empty($this->params['action'])) {
            $this->text = 'გთხოვთ აირჩიეთ ქმედება';
            return false;
        }

        /**
         * @var $item
         */
        $item = Item::find($value);

        if (is_null($item)) {
            $this->text = 'მოწყობილობა არ მოიძებნა';
            return false;
        }

        $action = $this->params['action'];

        if ( $action == 1 ) {

            if($item->action != 2 && $item->action != null) {
                $this->text = 'მოწყობილობა დაკავებულია';
                return false;
            }

        } else if ($action == 2) {

            if($item->action != 1) {
                $this->text = 'მოწყობილობა არ არის გაცემული';
                return false;
            }


            /**
             * @var $person Person
             */
            $person = Person::where('personal_number', $this->params['person_id'])->first();

            if (is_null($person)) {
                $this->text = 'აღნიშნული პირი არ მოიძებნა';
                return false;
            }

            if (!$person->status) {
                $this->text = 'აღნიშნული პირი არ არის აქტიური';
                return false;
            }

            $itemLog = ItemLog::where('item_id', $item->id)->where('action', 1)->orderBy('id', 'desc')->first();

            if ($itemLog->person_id != $person->id) {
                $this->text = 'აღნიშნული ნივთი არ გაუტანია ' . $person->getFullName() . '-ს';
                return false;
            }

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
