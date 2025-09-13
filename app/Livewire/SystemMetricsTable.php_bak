<?php

namespace App\Livewire;
use Livewire\Component;
use App\Models\SystemMetric;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;

class SystemMetricsTable extends Component
{
    use WithPagination;
    public $search = '';
    public $environmentFilter = '';
    public $cpuThreshold = '';
    public $memoryThreshold = '';
    public $sortField = 'timestamp';
    public $sortDirection = 'desc';
    public $showAll = false;
    public $perPage = 10;
    public $filterToday = true;
    protected $queryString = [
        'search',
        'environmentFilter',
        'cpuThreshold',
        'memoryThreshold',
        'sortField',
        'sortDirection',
        'showAll',
        'perPage',
        'filterToday',
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }
    public function updatedSearch()
    {
        if (strlen($this->search) > 10) {
            $this->search = substr($this->search, 0, 10);
        }

        $this->resetPage();
    }
    public function updatedEnvironmentFilter()
    {
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
    private function extractMemoryPercent($memoryUsage)
    {
        preg_match('/\(([\d.]+)%\)/', $memoryUsage, $matches);
        return floatval($matches[1] ?? 0);
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
        $query = SystemMetric::query();

        if ($this->filterToday) {
            $query->whereDate('timestamp', now()->toDateString());
        } else {
            $query->whereBetween('timestamp', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ]);
        }
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('hostname', 'like', '%' . $this->search . '%')
                  ->orWhere('environment', 'like', '%' . $this->search . '%');
            });
        }
        if ($this->environmentFilter) {
            $query->where('environment', $this->environmentFilter);
        }
        if ($this->cpuThreshold) {
            $query->where('cpu_usage', '>', $this->cpuThreshold);
        }

        if ($this->memoryThreshold) {
            $query->where('memory_usage', '>', $this->memoryThreshold);
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        $query->select([
            'hostname',
            'environment',
            'cpu_usage',
            'memory_usage',
            'disk_usage',
            'network_usage',
            'status',
            'extra1',
            'extra2',
            'timestamp',
            'pgver' // <-- Added pgver here
        ]);

        $metrics = $this->showAll ? $query->get() : $query->paginate($this->perPage);

        $collection = $this->showAll ? $metrics : $metrics->getCollection();

        $collection->transform(function ($metric) {
            $metric->memory_percent = $this->extractMemoryPercent($metric->memory_usage);
            $metric->env_color = $this->getColorForEnv($metric->environment);
            return $metric;
        });

        $uniqueCollection = $collection->unique(function ($item) {
            return $item->timestamp . '|' . $item->hostname . '|' . $item->environment;
        })->values();

        if ($this->showAll) {
            $metrics = $uniqueCollection;
        } else {
            $metrics->setCollection($uniqueCollection);
        }

        $environments = Cache::remember('distinct_environments', 3600, function () {
            return SystemMetric::select('environment')->distinct()->pluck('environment')->filter();
        });

        return view('livewire.system-metrics-table', compact('metrics', 'environments'));
    }
}
