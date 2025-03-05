<?php

namespace App\Repositories;

use App\Models\User;

class AuthRepository
{

    public function create($userData)
    {
        return User::create($userData);
    }

    public function checkAlreadyExists($email)
    {
        return User::where('email', $email)->exists();
    }

    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }
}