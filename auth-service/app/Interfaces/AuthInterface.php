<?php declare(strict_types=1);

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface AuthInterface
{
    public function createUser(array $request) : User;

    public function getUserById(int $id): User;

    public function getUsersLogs(): Collection;
}
