<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\LogOptions;

class SystemMetric extends Model
{
     

    public $timestamps = false;

    protected $table = 'system_metrics';

    protected $fillable = [
        'timestamp',
        'hostname',
        'environment',
        'cpu_usage',
        'memory_usage',
        'disk_usage',
        'network_usage',
        'status',
        'extra1',
        'extra2',
        'file_name',
        'load_status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['cpu_usage', 'memory_usage', 'disk_usage', 'network_usage', 'status'])
            ->logOnlyDirty()
            ->useLogName('system_metric');
    }
}
