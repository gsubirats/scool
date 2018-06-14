<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateJobSubstitution.
 *
 * @package App\Http\Requests
 */
class UpdateJobSubstitution extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('update-job-substitutions');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer',
            'start_at' => 'sometimes|required|date',
            'end_at' => 'sometimes|required|date',
        ];
    }
}
