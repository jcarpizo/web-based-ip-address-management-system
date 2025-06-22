<?php

namespace Database\Seeders;

use App\Models\IpAddress;
use Illuminate\Database\Seeder;

class IpAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ipAddresses = [
            [
                'label' => 'Test IP Address One',
                'ip_address' => '127.0.0.1',
                'comments' => 'This is from Seeders',
                'added_by_user_id' => 1,
                'updated_by_user_id' => 1,
            ],
            [
                'label' => 'Test IP Address Two',
                'ip_address' => '127.0.0.2',
                'comments' => 'This is from Seeders',
                'added_by_user_id' => 2,
                'updated_by_user_id' => 2,
            ],
            [
                'label' => 'Test IP Address Three',
                'ip_address' => '127.0.0.3',
                'comments' => 'This is from Seeders',
                'added_by_user_id' => 1,
                'updated_by_user_id' => 2,
            ]
        ];

        foreach ($ipAddresses as $ipAddress) {
            IpAddress::create($ipAddress);
        }
    }
}
