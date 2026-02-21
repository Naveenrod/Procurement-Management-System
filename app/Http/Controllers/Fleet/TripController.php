<?php
namespace App\Http\Controllers\Fleet;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\FleetRoute;
use App\Services\FleetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TripController extends Controller
{
    public function __construct(private readonly FleetService $fleetService) {}

    public function index(Request $request): View
    {
        $trips = Trip::with(['vehicle', 'driver', 'route'])
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()->paginate(15);
        return view('fleet.trips.index', compact('trips'));
    }

    public function create(): View
    {
        $vehicles = Vehicle::where('status', 'available')->get();
        $drivers = Driver::where('status', 'available')->get();
        $routes = FleetRoute::orderBy('name')->get();
        return view('fleet.trips.create', compact('vehicles', 'drivers', 'routes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $trip = Trip::create($request->validate(['vehicle_id' => 'required|exists:vehicles,id', 'driver_id' => 'required|exists:drivers,id', 'route_id' => 'nullable|exists:routes,id', 'scheduled_at' => 'required|date', 'notes' => 'nullable|string']));
        return redirect()->route('fleet.trips.show', $trip)->with('success', 'Trip scheduled.');
    }

    public function show(Trip $trip): View
    {
        $trip->load(['vehicle', 'driver', 'route', 'fuelLogs']);
        return view('fleet.trips.show', compact('trip'));
    }

    public function edit(Trip $trip): View
    {
        $vehicles = Vehicle::all();
        $drivers = Driver::all();
        $routes = FleetRoute::orderBy('name')->get();
        return view('fleet.trips.edit', compact('trip', 'vehicles', 'drivers', 'routes'));
    }

    public function update(Request $request, Trip $trip): RedirectResponse
    {
        $trip->update($request->validate(['scheduled_at' => 'required|date', 'notes' => 'nullable|string']));
        return redirect()->route('fleet.trips.show', $trip)->with('success', 'Trip updated.');
    }

    public function destroy(Trip $trip): RedirectResponse
    {
        $trip->delete();
        return redirect()->route('fleet.trips.index')->with('success', 'Trip deleted.');
    }

    public function start(Trip $trip): RedirectResponse
    {
        $this->fleetService->startTrip($trip);
        return redirect()->route('fleet.trips.show', $trip)->with('success', 'Trip started.');
    }

    public function complete(Trip $trip): RedirectResponse
    {
        $this->fleetService->completeTrip($trip);
        return redirect()->route('fleet.trips.show', $trip)->with('success', 'Trip completed.');
    }
}
