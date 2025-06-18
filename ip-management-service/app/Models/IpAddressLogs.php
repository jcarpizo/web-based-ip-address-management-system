<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpAddressLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'event',
        'old_value',
        'new_value',
        'added_by_user_id',
        'updated_by_user_id',
    ];
}
