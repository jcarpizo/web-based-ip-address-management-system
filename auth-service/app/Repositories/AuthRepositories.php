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

    public function getUserById(int $id): User
    {
        return User::find($id);
    }
}
