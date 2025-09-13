<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UatMetric;
use Carbon\Carbon;

class UatMetricsGraph extends Component
{
    public array $cpuChartData = [];
    public array $memoryChartData = [];
    public array $availableHosts = [];
    public string $selectedDate;

    public function mount(): void
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');

        // Ambil hostname dan IP address dari database
        $this->availableHosts = UatMetric::select('Database', 'IP_Address')
            ->whereNotNull('Database')
            ->whereNotNull('IP_Address')
            ->distinct()
            ->get()
            ->pluck('IP_Address', 'Database')
            ->toArray();

        $this->loadMetrics();
    }

    public function updatedSelectedDate(): void
    {
        $this->loadMetrics();
    }

    private function loadMetrics(): void
    {
        $metrics = UatMetric::select('Timestamp', 'Database', 'IP_Address', 'CPU', 'Memory')
            ->whereDate('Timestamp', $this->selectedDate)
            ->orderBy('Timestamp', 'asc')
            ->get();

        $grouped = $metrics->groupBy(function ($item) {
            $timestamp = Carbon::parse($item->Timestamp);
            $roundedMinute = (int)($timestamp->minute / 15) * 15;
            $roundedTime = $timestamp->copy()->minute($roundedMinute)->second(0);
            return $roundedTime->format('H:i') . '|' . $item->Database;
        });

        $this->cpuChartData = $this->formatChartData($grouped, 'CPU', $metrics);
        $this->memoryChartData = $this->formatChartData($grouped, 'Memory', $metrics);
    }

    private function formatChartData($groupedMetrics, string $type, $originalMetrics): array
    {
        $chartData = [
            'labels' => [],
            'datasets' => [],
        ];

        $envData = [];

        // Buat mapping hostname => IP
        $hostIpMap = $originalMetrics->pluck('IP_Address', 'Database')->toArray();

        foreach ($groupedMetrics as $key => $group) {
            [$datetime, $env] = explode('|', $key);

            if (!in_array($datetime, $chartData['labels'])) {
                $chartData['labels'][] = $datetime;
            }

            if (!isset($envData[$env])) {
                $envData[$env] = [];
            }

            $avg = round(
                $group->pluck($type)
                    ->map(function ($v) use ($type) {
                        if ($type === 'Memory') {
                            if (is_string($v) && preg_match('/(\d+)\s*\/\s*(\d+)/', $v, $matches)) {
                                $used = floatval($matches[1]);
                                $total = floatval($matches[2]);
                                return ($total > 0) ? ($used / $total) * 100 : 0;
                            }
                            return 0;
                        } else {
                            if (is_string($v)) {
                                $clean = preg_replace('/[^0-9.]/', '', $v);
                                return is_numeric($clean) ? floatval($clean) : 0;
                            }
                            return is_numeric($v) ? floatval($v) : 0;
                        }
                    })
                    ->filter(fn($v) => $v >= 0 && $v <= 100)
                    ->avg() ?? 0,
                2
            );

            $envData[$env][$datetime] = $avg;
        }

        sort($chartData['labels']);

        foreach ($envData as $env => $dataPerTime) {
            $data = [];
            foreach ($chartData['labels'] as $datetime) {
                $data[] = $dataPerTime[$datetime] ?? null;
            }

            $ip = $hostIpMap[$env] ?? 'IP tidak tersedia';
            $label = "{$env} ({$ip})";

            $chartData['datasets'][] = [
                'label' => $label,
                'data' => $data,
                'borderColor' => $this->getColorForHost($env),
                'borderWidth' => 1,
                'fill' => false,
                'tension' => 0.2,
            ];
        }

        return $chartData;
    }

    private function getColorForHost(string $host): string
    {
        $colorMap = [
            'stg' => '#1f77b4',
            'fuse' => '#ff7f0e',
            'prod' => '#2ca02c',
            'dev' => '#d62728',
            'qa' => '#9467bd',
            'pruworks' => '#8c564b',
            'epolicy' => '#e377c2',
            'training' => '#7f7f7f',
            'leads' => '#bcbd22',
            'newods' => '#17becf',
            'compliance' => '#aec7e8',
            'esub' => '#ffbb78',
            'dbapru' => '#98df8a',
            'prudbclm' => '#c5b0d5',
            'aob' => '#ff9896',
            'base' => '#c49c94',
            'uat' => '#f7b6d2',
            'nbwf' => '#dbdb8d',
            'omni' => '#9edae5',
            'dbpru' => '#393b79',
        ];

        if (isset($colorMap[$host])) {
            return $colorMap[$host];
        }

        $hash = md5($host);
        $r = hexdec(substr($hash, 0, 2));
        $g = hexdec(substr($hash, 2, 2));
        $b = hexdec(substr($hash, 4, 2));

        $minBrightness = 130;
        $brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;

        if ($brightness < $minBrightness) {
            $increase = $minBrightness - $brightness;
            $r = min(255, $r + $increase);
            $g = min(255, $g + $increase);
            $b = min(255, $b + $increase);
        }

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    public function render()
    {
        return view('livewire.uat-metrics-graph', [
            'cpuChartData' => $this->cpuChartData,
            'memoryChartData' => $this->memoryChartData,
            'selectedDate' => $this->selectedDate,
            'availableHosts' => $this->availableHosts,
        ])->layout('layouts.app');
    }
}
