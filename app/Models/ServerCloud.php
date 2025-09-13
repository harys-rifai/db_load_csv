<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerCloud extends Model
{
    protected $table = 'server_cloud'; // Optional if table name matches model name

    protected $fillable = [
        'name', 'appref', 'ip', 'environment', 'description', 'pic', 'tribe',
        'version', 'database_name', 'processor', 'memory', 'storage',
        'encryption', 'pii', 'remark'
    ];
}
