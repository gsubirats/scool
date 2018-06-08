<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TeacherForPendingTeachers.
 *
 * @package App\Http\Resources
 */
class TeacherForPendingTeachers extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'user_id' => $this->id,
            'teacher_id' => optional($this->teacher)->id,
            'code' => optional($this->teacher)->code,
            'hashid' => $this->hashid,
            'name' => $this->name,
            'email' => $this->email
        ];
    }
}
