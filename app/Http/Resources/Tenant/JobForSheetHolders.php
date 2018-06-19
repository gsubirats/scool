<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class JobForSheetHolders.
 *
 * @package App\Http\Resources\Tenant
 */
class JobForSheetHolders extends JsonResource
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
        if ($teacher = optional(optional($holder)->teacher)) {
            $holder_code = $teacher->code;
            $holder_description = $teacher->code . ' ' . $holder_name;
        } else {
            $holder_description = $holder_name;
        }

        return [
            'id' => $this->id,
            'code' => $this->code,
            'holder_hashid' => optional($holder)->hash_id,
            'holder_code' => $holder_code,
            'holder_name' => $holder_name,
            'holder_email' => $holder_email,
            'holder_description' => $holder_description
        ];
    }
}
