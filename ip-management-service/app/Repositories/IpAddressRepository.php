<?php

namespace App\Repositories;

use App\Interfaces\IpAddressInterface;
use App\Models\IpAddress;
use RuntimeException;

use Illuminate\Database\Eloquent\Collection;

class IpAddressRepository implements IpAddressInterface
{
    public function create(array $data): IpAddress
    {
        return IpAddress::create($data);
    }

    /**
     * @throws RuntimeException
     */
    public function update(int $id, array $data): ?IpAddress
    {
        $ipAddress = $this->find($id);
        $ipAddress->update($data);
        return $ipAddress;
    }

    /**
     * @throws RuntimeException
     */
    public function delete(int $id): ?IpAddress
    {
        $ipAddress = $this->find($id);
        $ipAddress->delete();
        return $ipAddress;
    }

    public function all(): Collection
    {
        return IpAddress::orderBy('updated_at', 'desc')->get();
    }

    /**
     * @throws RuntimeException
     */
    public function find(int $id): ?IpAddress
    {
        $ipAddress =  IpAddress::where('id',$id)->get()->first();
        if (!$ipAddress) {
            throw new RuntimeException("IP Address not found");
        }
        return $ipAddress;
    }
}
