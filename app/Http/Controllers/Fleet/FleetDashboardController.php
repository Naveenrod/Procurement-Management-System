<?php
namespace App\Http\Controllers\Fleet;

use App\Http\Controllers\Controller;
use App\Services\FleetService;
use Illuminate\View\View;

class FleetDashboardController extends Controller
{
    public function __construct(private readonly FleetService $fleetService) {}

    public function index(): View
    {
        $stats = $this->fleetService->getFleetStats();
        return view('fleet.dashboard', compact('stats'));
    }
}
