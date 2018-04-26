<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateUserTenant.
 *
 * @package App\Http\Requests
 */
class CreateUserTenant extends FormRequest
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
            'subdomain' => 'required|alpha_dash|unique:tenants',
            'password' => 'required|min:6|confirmed'
        ];
    }
}
