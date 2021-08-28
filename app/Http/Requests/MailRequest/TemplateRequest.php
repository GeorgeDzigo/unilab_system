<?php

namespace App\Http\Requests\MailRequest;

use Illuminate\Foundation\Http\FormRequest;

class TemplateRequest extends FormRequest
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
            'name' => 'required | min:2',
            'headerImage' => 'mimes:jpeg,jpg,png,gif,raw',
            'footerImage' => 'mimes:jpeg,jpg,png,gif,raw',
        ];
    }
}
