<?php

namespace App\Models\Traits;

/**
 * Class HasSuperAdmins.
 *
 * @package App\Models\Traits
 */
class HasSuperAdmins
{
    /**
     * Is user super admin?
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return true;
    }
}