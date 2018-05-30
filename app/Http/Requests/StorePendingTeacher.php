<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StorePendingTeacher.
 *
 * @package App\Http\Requests
 */
class StorePendingTeacher extends FormRequest
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
            'name' => 'required',
            'sn1' => 'required',
            'identifier' => 'required',
            'birthdate' => 'required',
            'street' => 'required',
            'number' => 'required',
            'postal_code' => 'required',
            'locality' => 'required',
            'province' => 'required',
            'email' => 'required',
            'telephone' => 'required',
            'degree' => 'required',
            'force_id' => 'required',
            'start_date' => 'required',
            'administrative_status_id' => 'required',
            'photo' => 'sometimes|required',
            'identifier_photocopy' => 'sometimes|required',
        ];
    }
}
