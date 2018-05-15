<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreStaff.
 *
 * @package App\Http\Requests
 */
class StoreStaff extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('store-staff');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required|unique:staff|max:4',
            'type' => 'required',
            'family' => "required_if:type,Professor/a",
            'specialty' => "required_if:type,Professor/a",
        ];
    }
}
