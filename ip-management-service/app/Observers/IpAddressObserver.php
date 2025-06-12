<?php

namespace App\Observers;

use App\Models\IpAddress;
use App\Models\IpAddressLogs;

class IpAddressObserver
{
    public function created(IpAddress $ipAddress): void
    {
        $this->log($ipAddress, 'created');
    }

    public function updated(IpAddress $ipAddress): void
    {
        $this->log($ipAddress, 'updated');
    }

    private function log(IPAddress $model, string $event): void
    {
        $old = $event === 'updated' ? json_encode($model->getOriginal()) : null;

        $new = json_encode($model->getAttributes());

        IpAddressLogs::create([
            'user_id'        => 1,
            'ip_address_id'  => $model->id,
            'event'          => $event,
            'old_value'      => $old,
            'new_value'      => $new,
        ]);
    }
}
