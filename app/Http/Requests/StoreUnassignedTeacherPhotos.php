<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreUnassignedTeacherPhotos.
 *
 * @package App\Http\Requests
 */
class StoreUnassignedTeacherPhotos extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        Auth::shouldUse('api');
        return Auth::user()->can('store-teacher-photo');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'photos' => 'required|mimetypes:application/zip,application/octet-stream'
        ];
    }
}
