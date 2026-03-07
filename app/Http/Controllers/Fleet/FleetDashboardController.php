<?php
namespace App\Http\Controllers\Fleet;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Services\FleetService;
use Illuminate\View\View;

class FleetDashboardController extends Controller
{
    public function __construct(private readonly FleetService $fleetService) {}

    public function index(): View
    {
        $stats = $this->fleetService->getFleetStats();

        $activeTrips = Trip::with(['vehicle', 'driver', 'route'])
            ->where('status', 'in_progress')
            ->latest('started_at')
            ->limit(10)
            ->get();

        $recentTrips = Trip::with(['vehicle', 'driver', 'route'])
            ->where('status', 'completed')
            ->whereMonth('completed_at', now()->month)
            ->latest('completed_at')
            ->limit(5)
            ->get();

        return view('fleet.dashboard', compact('stats', 'activeTrips', 'recentTrips'));
    }
}
