<?php

namespace App\Repositories;

use App\Models\User;

/**
 * Class UserRepository.
 *
 * @package App\Repositories
 */
class UserRepository
{
    /**
     * Store user.
     *
     * @param $request
     * @return mixed
     */
    public function store($user)
    {

        $password = str_random();
        if (isset($user->password)) {
            $password = $user->password;
        }
        $photo = null;
        $photo_hash = null;
        if (isset($user->photo)) {
            $photo = $user->photo;
            $photo_hash = md5($user->photo);
        }

        return User::create([
            'name' => $user->name,
            'email' => $user->email,
            'password' => sha1($password),
            'photo' => $photo,
            'photo_hash' => $photo_hash
        ]);
    }
}