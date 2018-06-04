<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Teacher.
 *
 * @package App\Http\Resources
 */
class Teacher extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $job = null;
        $job_start_at = null;
        $job_end_at = null;
        $job_description = null;
        if ($jobObject = optional(optional($this->user)->jobs)[0]) {
            $job =  $jobObject->fullCode;
            $job_description =  $jobObject->description;
            $job_start_at =  $jobObject->employee->start_at;
            $job_end_at =  $jobObject->employee->end_at;
        }
        return [
            'id' => $this->id,
            'code' => $this->code,
            'formatted_created_at_diff' => $this->formatted_created_at_diff,
            'formatted_created_at' => $this->formatted_created_at,
            'formatted_updated_at' => $this->formatted_updated_at,
            'formatted_updated_at_diff' => $this->formatted_updated_at_diff,
            'hashid' => optional($this->user)->hashid,
            'name' => optional($this->user)->name,
            'email' => optional($this->user)->email,
            'fullname' => optional(optional($this->user)->person)->fullname,
            'department_code' => optional($this->department)->code,
            'department' => optional($this->department)->name,
            'specialty' => optional($this->specialty)->name,
            'specialty_code' => optional($this->specialty)->code,
            'family' => optional(optional($this->specialty)->family)->name,
            'family_code' => optional(optional($this->specialty)->family)->code,
            'administrative_status' => optional($this->administrativeStatus)->name,
            'administrative_status_code' => optional($this->administrativeStatus)->code,
            'job' => $job,
            'job_description' => $job_description,
            'job_start_at' => $job_start_at,
            'job_end_at' => $job_end_at,
            'full_search' => $this->fullsearch
        ];
    }
}
