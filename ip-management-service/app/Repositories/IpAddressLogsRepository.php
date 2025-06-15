<?php

namespace App\Repositories;

use App\Interfaces\IpAddressLogsInterface;
use App\Models\IpAddressLogs;

use Illuminate\Database\Eloquent\Collection;

class IpAddressLogsRepository implements IpAddressLogsInterface
{
    public function all(): Collection
    {
        return IpAddressLogs::orderBy('updated_at', 'desc')->get();
    }
}
