<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UatMetric extends Model
{
    use HasFactory;

    protected $table = 'uatmetrics';

    protected $fillable = [
        'Timestamp',
        'Hostname',
        'IP_Address',
        'Database',
        'CPU',
        'Memory',
        'DiskVolGroupAvg',
        'DiskDataAvg',
        'ServerStatus',
        'LongQueryCount',
        'LockingCount',
        'PostgresVersion',
        'flag',
        'state',
        'created_at',
    ];

    public $timestamps = false;

    // Accessor agar bisa pakai lowercase
    public function getTimestampAttribute()
    {
        return $this->attributes['Timestamp'];
    }

    public function getCpuUsageAttribute()
    {
        return $this->attributes['CPU'];
    }
}
