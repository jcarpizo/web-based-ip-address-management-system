<?php declare(strict_types = 1);

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface IpAddressLogsInterface
{
    /**
     * @return Collection
     */
    public function all(): Collection;
}
