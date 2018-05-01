<?php

namespace App\Exceptions;

use InvalidArgumentException;

/**
 * Class UserTypeDoesNotExist.
 *
 * @package App\Exceptions
 */
class UserTypeDoesNotExist extends InvalidArgumentException
{
    /**
     * Named.
     *
     * @param string $typeName
     * @return static
     */
    public static function named(string $typeName)
    {
        return new static("There is no user type named `{$typeName}`.");
    }
}
