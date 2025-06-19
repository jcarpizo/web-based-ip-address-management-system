<?php declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\AuthInterface;
use App\Models\User;
use App\Models\UserLogs;
use Illuminate\Database\Eloquent\Collection;

class AuthRepositories implements AuthInterface
{
    /**
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User
    {
        return User::create($data) ;
    }

    /**
     * @param int $id
     * @return User
     */
    public function getUserById(int $id): User
    {
        return User::find($id);
    }

    /**
     * @return Collection
     */
    public function getUsersLogs(): Collection
    {
        return UserLogs::with('user')->orderBy('updated_at', 'desc')->get();
    }
}
