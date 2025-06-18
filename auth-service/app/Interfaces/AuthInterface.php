<?php

namespace App\Interfaces;

use App\Models\User;

interface AuthInterface
{
    public function createUser(array $request) : User;

    public function getUserById(int $id): User;
}
