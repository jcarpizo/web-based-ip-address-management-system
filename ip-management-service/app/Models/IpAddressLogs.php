<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpAddressLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address_id',
        'event',
        'old_value',
        'new_value',
    ];
}
