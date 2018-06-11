<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ApprovedTeacher.
 *
 * @package App\Http\Requests
 */
class ApprovedTeacher extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('approve-teacher');
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
            'street' => 'required',
            'postal_code' => 'required',
            'locality' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'titulacio_acces' => 'required',
            'administrative_status_id' => 'required',
            'photo' => 'sometimes|required',
            'identifier_photocopy' => 'sometimes|required',
            'username' => 'required',
            'job_id' => 'required',
            'locality_id' => 'required_without:locality',
            'province_id' => 'required'
        ];
    }
}
