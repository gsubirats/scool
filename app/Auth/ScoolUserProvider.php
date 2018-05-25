<?php

namespace App\Auth;

use App\Models\Person;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Str;

/**
 * Class ScoolUserProvider.
 *
 * @package App\Auth
 */
class ScoolUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
            (count($credentials) === 1 &&
                array_key_exists('password', $credentials))) {
            return;
        }
        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->createModel()->newQuery();

        foreach ($credentials as $key => $value) {
            if (! Str::contains($key, 'password')) {
                $query->where($key, $value);
            }
        }

        if ($query->first() === null) {
            // Try to search email in personal data
            foreach ($credentials as $key => $value) {
                if (! Str::contains($key, 'password')) {
                    $person = Person::where($key, $value)->first();
                    if ($person && $person->user) {
                        return $person->user;
                    }
                }
                return null;
            }
        } else {
            return $query->first();
        }
    }
}