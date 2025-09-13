<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = Activity::with('causer')->latest()->paginate(10);

        return view('activity-logs.index', compact('logs'));
    }
}
