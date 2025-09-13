<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Activitylog\Models\Activity;
use Livewire\WithPagination;

class ActivityLogDashboard extends Component
{
    use WithPagination;

    public function render()
    {
        $logs = Activity::with('causer')->latest()->paginate(10);

        return view('livewire.activity-log-dashboard', [
            'logs' => $logs,
        ]);
    }
}

