<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\IpAddress;

interface IpAddressInterface
{
    public function create(array $data): IpAddress;

    public function update(int $id, array $data): ?IpAddress;

    public function delete(int $id): ?IpAddress;

    public function find(int $id): ?IpAddress;

    public function all(?int $currentUserId): Collection;
}
