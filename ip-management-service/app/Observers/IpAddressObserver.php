<?php declare(strict_types = 1);

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

    public function deleted(IpAddress $ipAddress): void
    {
        $this->log($ipAddress, 'deleted');
    }

    private function log(IPAddress $model, string $event): void
    {
        $oldValues = [
            'label' => $model->getOriginal('label'),
            'comments' => $model->getOriginal('comments'),
            'ip_address' => $model->getOriginal('ip_address'),
        ];

        $old = $event === 'updated' ? json_encode($oldValues) : ($event == 'deleted' ? json_encode($oldValues) : null);
        $new = $event === 'deleted' ? null : json_encode($model->only('label', 'comments', 'ip_address'));

        IpAddressLogs::create([
            'event'          => $event,
            'old_value'      => $old,
            'new_value'      => $new,
            'added_by_user_id' =>  $model->only('added_by_user_id')['added_by_user_id'],
            'updated_by_user_id' =>  $model->only('updated_by_user_id')['updated_by_user_id'],
        ]);
    }
}
