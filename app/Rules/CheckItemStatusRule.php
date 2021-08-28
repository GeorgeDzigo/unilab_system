<?php

namespace App\Rules;

use App\Models\Item;
use Illuminate\Contracts\Validation\Rule;

class CheckItemStatusRule implements Rule
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
         * @var $item
         */
        $item = Item::find($value);

        if (is_null($item)) {
            $this->text = 'მოწყობილობა არ მოიძებნა';
            return false;
        }

        if ( !$item->status) {
            $this->text = 'მოწყობილობა არ არის აქტიური, ვერ გაატარებთ!';
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
