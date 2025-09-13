<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GraphController extends Controller
{
    public function index()
    {
        // Ambil data dari tabel SystemMetric
        $metrics = DB::table('system_metrics')
            ->select('hostname', 'timestamp', 'cpu_usage')
            ->orderBy('timestamp')
            ->get();

        // Format data untuk grafik
        $grouped = $metrics->groupBy('hostname');

        return view('system-metrics.graph', compact('grouped'));
    }
}

