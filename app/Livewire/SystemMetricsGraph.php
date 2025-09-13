<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\SystemMetric;

class SystemMetricsGraph extends Component
{
    public $groupedData = [];

    public function mount()
    {
        $metrics = SystemMetric::orderBy('timestamp')->get();

        $this->groupedData = $metrics->groupBy('hostname')->map(function ($group) {
            return $group->map(function ($item) {
                return [
                    'timestamp' => $item->timestamp,
                    'cpu_usage' => $item->cpu_usage,
                ];
            });
        });
    }

    public function render()
    {
        return view('livewire.system-metrics-graph', [
            'groupedData' => $this->groupedData
        ]);
    }
}


