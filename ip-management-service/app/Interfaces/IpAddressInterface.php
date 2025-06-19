<?php declare(strict_types = 1);

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\IpAddress;

interface IpAddressInterface
{
    /**
     * @param array $data
     * @return IpAddress
     */
    public function create(array $data): IpAddress;

    /**
     * @param int $id
     * @param array $data
     * @return IpAddress|null
     */
    public function update(int $id, array $data): ?IpAddress;

    /**
     * @param int $id
     * @return IpAddress|null
     */
    public function delete(int $id): ?IpAddress;

    /**
     * @param int $id
     * @return IpAddress|null
     */
    public function find(int $id): ?IpAddress;

    /**
     * @param int|null $currentUserId
     * @return Collection
     */
    public function all(?int $currentUserId): Collection;
}
