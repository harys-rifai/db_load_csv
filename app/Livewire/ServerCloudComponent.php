<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ServerCloud;
  use Illuminate\Support\Facades\DB;

class ServerCloudComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $filterEnvironment = '';
    public $readyToLoad = false;

    protected $queryString = ['search', 'sortField', 'sortDirection', 'filterEnvironment'];

    protected $allowedSortFields = [
        'name', 'appref', 'ip', 'environment', 'description', 'pic', 'tribe',
        'version', 'database_name', 'processor', 'memory', 'storage',
        'encryption', 'pii', 'remark'
    ];

    public function loadServers()
    {
        $this->readyToLoad = true;
    }


    public function updatingSearch()
{
    $this->resetPage();
    $this->loadServers(); // Tambahkan ini
}


    public function updatingFilterEnvironment()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if (!in_array($field, $this->allowedSortFields)) return;

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function applyFilter()
    {
        $this->readyToLoad = true;
        $this->resetPage();
        $this->loadServers();
    }

    public function render()
        {
            $servers = collect();

            if ($this->readyToLoad) {
                $servers = ServerCloud::query()
                    ->when($this->search, function ($query) {
    $query->where(function ($subQuery) {
        $subQuery->where('name', 'ILIKE', '%' . $this->search . '%')
                 ->orWhere('appref', 'ILIKE', '%' . $this->search . '%')
                 ->orWhere('ip', 'ILIKE', '%' . $this->search . '%')
                 ->orWhere('environment', 'ILIKE', '%' . $this->search . '%')
                 ->orWhere('description', 'ILIKE', '%' . $this->search . '%')
                 ->orWhere('version', 'ILIKE', '%' . $this->search . '%')
                 ->orWhere('database_name', 'ILIKE', '%' . $this->search . '%')
                 ->orWhere('remark', 'ILIKE', '%' . $this->search . '%');
    });
})

                    ->when($this->filterEnvironment, function ($query) {
                        $query->where('environment', $this->filterEnvironment);
                    })
                    ->orderBy($this->sortField, $this->sortDirection)
                    ->paginate(10);
            }

    return view('livewire.server-cloud-component', [
        'servers' => $servers
    ])->layout('layouts.app');
}









}
