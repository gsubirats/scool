<?php

namespace App\Http\Resources\Tenant;

/**
 * Class UserTypesCollection.
 *
 * @package App\Http\Resources\Tenant
 */
class UserTypesCollection
{

    protected $userTypes;

    /**
     * UserTypesCollection constructor.
     */
    public function __construct($userTypes)
    {
        $this->userTypes = $userTypes;
    }

    /**
     * @return mixed
     */
    public function transform()
    {
        return $this->userTypes->map(function($userType) {
            return [
                'id' => $userType->id,
                'name' => $userType->name,
                'roles' => $userType->roles->pluck('name')->toArray()
            ];
        });
    }
}