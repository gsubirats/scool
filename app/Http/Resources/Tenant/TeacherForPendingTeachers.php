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
            'user_id' => optional($this->user)->id,
            'teacher_id' => $this->id,
            'code' => $this->code,
            'hashid' => optional($this->user)->hashid,
            'name' => optional($this->user)->name,
            'email' => optional($this->user)->email,
        ];
    }
}
