<?php

namespace Database\Seeders;

use App\Models\IpAddressAppKey;
use Illuminate\Database\Seeder;

class IpAddressAppKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         IpAddressAppKey::create([
             'name' => 'ip-address-app-key',
             'key' => '3qohBUb4RJLUduNQ2ArCrokddmtmckl42vZ1g0IN'
         ]);
    }
}
