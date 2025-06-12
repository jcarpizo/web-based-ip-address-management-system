<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IpAddress extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'label',
        'ip_address',
        'comment',
        'added_by_user_id',
        'updated_by_user_id',
    ];
}
