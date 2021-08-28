<?php

namespace App\Http\Requests\Admin\Person;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'                => 'required',
            'last_name'                 => 'required',
            'personal_number'           => 'required|digits:11',
            'birth_date'                => 'nullable|date',
            'gender'                    => 'required',
            'unilab_email'              => 'nullable|email',
            'personal_email'            => 'nullable|email',
//            'card_id'                   => 'required',
            'positions.*.date_type'     => 'required',
            'positions.*.position'      => 'required',
            'positions.*.start_to'      => 'required_if:positions.*.date_type,1',
            'positions.*.department'    => 'required'
        ];
    }

    /**
     * @return array|void
     */
    public function messages()
    {
        return [
            'positions.*.date_type.required'        => 'პოზიციის ვადის არჩევა აუცილებელია',
            'positions.*.start_to.required_if'      => 'აუცილებელია დაწყება/დასრულების თარიღის მითითება',
            'positions.*.position.required'         => 'აუცილებელია პოზიციის მითითება',
            'positions.*.department.required'       => 'მიმართულების მითითება აუცილებელია'
        ];
    }

}
