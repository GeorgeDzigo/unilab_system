<?php

namespace App\Http\Requests\ItemLog;

use App\Repositories\Contracts\IPersonRepository;
use App\Rules\CheckItemStatusRule;
use App\Rules\CheckPositionStatus;
use App\Rules\ItemCheckAction;
use App\Rules\PersonCheckStatus;
use Illuminate\Foundation\Http\FormRequest;

class SaveLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param IPersonRepository $personRepository
     * @return array
     */
    public function rules(IPersonRepository $personRepository)
    {
        return [
            'item_id'           => ['required', new CheckItemStatusRule, new ItemCheckAction($this->request->all())],
            'person_id'         => ['required', new PersonCheckStatus],
            'action'            => 'required',
            'position'          => [new CheckPositionStatus($this->request->all(), $personRepository)]
        ];
    }

}
