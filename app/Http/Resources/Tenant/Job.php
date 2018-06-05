<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Job.
 *
 * @package App\Http\Resources\Tenant
 */
class Job extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $holder = null;
        if ($this->holders->first()) {
            $holder = $this->holders->first();
        }
        $holder_code = '';
        $holder_name = optional($holder)->name;
        if ($teacher = optional(optional($holder)->teacher)) {
            $holder_code = $teacher->code;
            $holder_description = $teacher->code . ' ' . $holder_name;
        } else {
            $holder_description = $holder_name;
        }

        $active_user_name = optional($this->activeUser)->name;
        if ($teacher = optional(optional($this->activeUser)->teacher)) {
            $active_user_code = $teacher->code;
            $active_user_description = $teacher->code . ' ' . $active_user_name;
        } else {
            $active_user_description = $holder_name;
        }

        $family = optional($this->family)->name;
        $family_code = optional($this->family)->code;
        if ($family_code) {
            $family_description = $family_code . ' ' . $family;
        } else {
            $family_description = $family;
        }

        $specialty = optional($this->specialty)->name;
        $specialty_code = optional($this->specialty)->code;
        if ($specialty_code) {
            $specialty_description = $specialty_code . ' ' . $specialty;
        } else {
            $specialty_description = $specialty;
        }

        $substitutes = [];
        foreach ($this->substitutes as $substitute) {
            $substitutes[] = [
                'hash_id' => $substitute->hash_id,
                'name' => $substitute->name,
                'code' => $code = optional($substitute->teacher)->code,
                'description' => $code . ' ' . $substitute->name,
            ];
        }
        return [
            'id' => $this->id,
            'type' => optional($this->type)->name,
            'code' => $this->code,
            'holder_hashid' => optional($holder)->hash_id,
            'holder_code' => $holder_code,
            'holder_name' => $holder_name,
            'holder_description' => $holder_description,
            'active_user_hash_id' => optional($this->activeUser)->hashid,
            'active_user_code' => $active_user_code,
            'active_user_name' => $active_user_name,
            'active_user_description' => $active_user_description,
            'substitutes' => $substitutes,
            'fullcode' => $this->fullcode,
            'order' => $this->order,
            'family' => $family,
            'family_code' => $family_code,
            'family_description' => $family_description,
            'specialty' => $specialty,
            'specialty_code' => $specialty_code,
            'specialty_description' => $specialty_description,
            'formatted_created_at_diff' => $this->formatted_created_at_diff,
            'formatted_created_at' => $this->formatted_created_at,
            'formatted_updated_at' => $this->formatted_updated_at,
            'formatted_updated_at_diff' => $this->formatted_updated_at_diff,
            'notes' => $this->notes
        ];
    }
}
