<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class JobForSheet.
 *
 * @package App\Http\Resources\Tenant
 */
class JobForSheet extends JsonResource
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
        $holder_name = optional($holder)->name;
        $holder_email = optional($holder)->email;

        $active_user_name = optional($this->activeUser)->name;
        $active_user_email = optional($this->activeUser)->email;
        if ($teacher = optional(optional($this->activeUser)->teacher)) {
            $active_user_code = $teacher->code;
            $active_user_description = $teacher->code . ' ' . $active_user_name . ' - ' . $active_user_email;
        } else {
            $active_user_description = $holder_name . '-' . $holder_email;
        }
        return [
            'id' => $this->id,
            'active_user_hash_id' => optional($this->activeUser)->hashid,
            'active_user_code' => $active_user_code,
            'active_user_name' => $active_user_name,
            'active_user_email' => $active_user_email,
            'active_user_description' => $active_user_description,
        ];
    }
}
