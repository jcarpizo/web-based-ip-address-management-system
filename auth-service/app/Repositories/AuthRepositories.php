<?php

namespace App\Repositories;

use App\Interfaces\AuthInterface;
use App\Models\User;

class AuthRepositories implements AuthInterface
{
    public function createUser(array $data): User
    {
        return User::create($data) ;
    }
}
