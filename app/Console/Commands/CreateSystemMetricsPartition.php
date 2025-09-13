<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateSystemMetricsPartition extends Command
{
    protected $signature = 'partition:create-system-metrics';
    protected $description = 'Create quarterly partitions for system_metrics table if not exists';

    public function handle()
    {
        $year = now()->year;
        $quarters = [
            ['01-01', '04-01', 'q1'],
            ['04-01', '07-01', 'q2'],
            ['07-01', '10-01', 'q3'],
            ['10-01', '01-01', 'q4'], // next year
        ];

        foreach ($quarters as [$start, $end, $label]) {
            $startDate = "{$year}-{$start}";
            $endDate = $label === 'q4' ? ($year + 1) . "-{$end}" : "{$year}-{$end}";
            $partitionName = "system_metrics_{$year}_{$label}";

            $sql = "
                DO $$
                BEGIN
                    IF NOT EXISTS (
                        SELECT 1 FROM pg_class WHERE relname = '{$partitionName}'
                    ) THEN
                        EXECUTE '
                            CREATE TABLE {$partitionName} PARTITION OF system_metrics
                            FOR VALUES FROM (''{$startDate}'') TO (''{$endDate}'');
                        ';
                    END IF;
                END $$;
            ";

            DB::unprepared($sql);
            $this->info("Partition {$partitionName} checked/created.");
        }
    }
}
