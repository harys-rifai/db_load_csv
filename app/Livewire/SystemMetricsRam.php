<?php

namespace App\Livewire;
use Livewire\Component;
use App\Models\SystemMetric;
use Carbon\Carbon;

class SystemMetricsRam extends Component
{
    public $memoryChartData = [];
    public $selectedDate;
    public function mount()
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');
        $this->loadMetrics();
    }

    public function updatedSelectedDate()
    {
        $this->loadMetrics();
    }
    private function loadMetrics()
    {
        $metrics = SystemMetric::whereDate('timestamp', $this->selectedDate)
            ->orderBy('timestamp', 'asc')
            ->get()
            ->unique(function ($item) {
                return $item->timestamp . '|' . $item->hostname . '|' . $item->environment;
            });

        $grouped = $metrics->groupBy(function ($item) {
            $timestamp = Carbon::parse($item->timestamp);
            $roundedMinute = (int)($timestamp->minute / 15) * 15;
            $roundedTime = $timestamp->copy()->minute($roundedMinute)->second(0);
            return $roundedTime->format('H:i') . '|' . $item->environment;
        });

        $this->memoryChartData = $this->formatMemoryChartData($grouped);
    }

    private function formatMemoryChartData($groupedMetrics)
    {
        $chartData = [
            'labels' => [],
            'datasets' => [],
        ];

        $envData = [];

        foreach ($groupedMetrics as $key => $group) {
            [$datetime, $env] = explode('|', $key);

            if (!in_array($datetime, $chartData['labels'])) {
                $chartData['labels'][] = $datetime;
            }

            if (!isset($envData[$env])) {
                $envData[$env] = [];
            }

            $memoryAvg = round(
                $group->pluck('memory_usage')
                    ->map(function ($v) {
                        if (is_string($v)) {
                            // Ambil angka di dalam tanda kurung (misalnya: (10.65%))
                            preg_match('/\\((\\d+(\\.\\d+)?)%\\)/', $v, $matches);
                            return isset($matches[1]) ? floatval($matches[1]) : 0;
                        }
                        return is_numeric($v) ? floatval($v) : 0;
                    })
                    ->filter(fn($v) => $v >= 0 && $v <= 100)
                    ->avg() ?? 0,
                2
            );

            $envData[$env][$datetime] = $memoryAvg;
        }

        sort($chartData['labels']);

        foreach ($envData as $env => $dataPerTime) {
            $data = [];
            foreach ($chartData['labels'] as $datetime) {
                $data[] = $dataPerTime[$datetime] ?? null;
            }

            $chartData['datasets'][] = [
                'label' => $env,
                'data' => $data,
                'borderColor' => $this->getColorForEnv($env),
                'borderWidth' => 1,
                'fill' => false,
                'tension' => 0.2,
            ];
        }

        return $chartData;
    }

    private function getColorForEnv($env)
    {
        $colorMap = [
            'STG' => 'blue',
            'DBADB' => 'green',
            'PROD' => 'red',
            'DEV' => 'orange',
            'QA' => 'purple',
            'PRUWORKS' => '#1f77b4',
            'EPOLICY' => '#ff7f0e',
            'TRAINING' => '#2ca02c',
            'LEADS' => '#d62728',
            'NEWODS' => '#9467bd',
            'COMPLIANCE' => '#8c564b',
            'ESUB' => '#e377c2',
            'DBAPRU' => '#7f7f7f',
            'PRUDBCLM' => '#bcbd22',
            'AOB' => '#17becf',
            'BASE' => '#aec7e8',
            'MAGNUMPURE' => '#ffbb78',
            'NBWF' => '#98df8a',
            'OMNI' => '#ff9896',
            'AICOE' => '#c5b0d5',
        ];

        return $colorMap[$env] ?? 'gray';
    }

    public function render()
    {
        return view('livewire.system-metrics-ram', [
            'memoryChartData' => $this->memoryChartData,
            'selectedDate' => $this->selectedDate,
        ]);
    }
}
