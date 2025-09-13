<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UatMetric;
use Livewire\WithPagination;

class UatMetricsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'Timestamp';
    public $sortDirection = 'desc';
    public $showAll = false;
    public $perPage = 10;
    public $filterToday = true;

    protected $queryString = [
        'search',
        'sortField',
        'sortDirection',
        'showAll',
        'perPage',
        'filterToday',
    ];

    public function sortBy($label)
    {
        $field = $this->getSortableColumn($label);

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    private function getSortableColumn($label)
    {
        $map = [
            'Timestamp' => 'Timestamp',
            'Hostname' => 'Hostname',
            'IP' => 'IP_Address',
            'Database' => 'Database',
            'CPU' => 'CPU',
            'Memory' => 'Memory',
            'Disk Vol' => 'DiskVolGroupAvg',
            'Disk Data' => 'DiskDataAvg',
            'Status' => 'ServerStatus',
            'LongQuery' => 'LongQueryCount',
            'Locking' => 'LockingCount',
            'Version' => 'PostgresVersion',
            'Flag' => 'flag',
            'State' => 'state',
        ];

        return $map[$label] ?? 'Timestamp';
    }

    public function updatedSearch()
    {
        if (strlen($this->search) > 10) {
            $this->search = substr($this->search, 0, 10);
        }

        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedFilterToday()
    {
        $this->resetPage();
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    private function extractCpuPercent($cpuUsage)
    {
        preg_match('/([\d.]+)%/', $cpuUsage, $matches);
        return floatval($matches[1] ?? 0);
    }

    private function extractMemoryPercent($memoryUsage)
    {
        preg_match('/\(([\d.]+)%\)/', $memoryUsage, $matches);
        return floatval($matches[1] ?? 0);
    }

    private function getColorForEnv($env)
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

        return $colorMap[$env] ?? 'gray';
    }

    public function render()
    {
        $query = UatMetric::query();

        if ($this->filterToday) {
            $query->whereBetween('Timestamp', [
                now()->startOfDay(),
                now()->endOfDay()
            ]);
        } else {
            $query->whereBetween('Timestamp', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ]);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('Hostname', 'like', '%' . $this->search . '%')
                  ->orWhere('Database', 'like', '%' . $this->search . '%');
            });
        }

        $allowedSortFields = [
            'Timestamp', 'Hostname', 'IP_Address', 'Database', 'CPU', 'Memory',
            'DiskVolGroupAvg', 'DiskDataAvg', 'ServerStatus', 'LongQueryCount',
            'LockingCount', 'PostgresVersion', 'flag', 'state'
        ];

        if (in_array($this->sortField, $allowedSortFields)) {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $query->select([
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
        ]);

        $metrics = $this->showAll ? $query->get() : $query->paginate($this->perPage);

        $collection = $this->showAll ? $metrics : $metrics->getCollection();

        $collection->transform(function ($metric) {
            $metric->memory_percent = $this->extractMemoryPercent($metric->Memory);
            $metric->cpu_percent = $this->extractCpuPercent($metric->CPU);
            $metric->db_color = $this->getColorForEnv($metric->Database);
            return $metric;
        });

        $uniqueCollection = $collection->unique(function ($item) {
            return $item->Timestamp . '|' . $item->Hostname . '|' . $item->Database;
        })->values();

        if ($this->showAll) {
            $metrics = $uniqueCollection;
        } else {
            $metrics->setCollection($uniqueCollection);
        }

        return view('livewire.uat-metrics-table', [
            'metrics' => $metrics,
        ])->layout(\App\View\Components\AppLayout::class);
    }
}
