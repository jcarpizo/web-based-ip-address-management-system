<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface IpAddressLogsInterface
{
    public function all(): Collection;
}
