<?php
namespace App\Http\Controllers\Fleet;

use App\Http\Controllers\Controller;
use App\Models\FuelLog;
use App\Models\Vehicle;
use App\Services\FleetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FuelLogController extends Controller
{
    public function __construct(private readonly FleetService $fleetService) {}

    public function index(): View
    {
        $fuelLogs = FuelLog::with(['vehicle', 'trip'])->latest()->paginate(20);
        return view('fleet.fuel-logs.index', compact('fuelLogs'));
    }

    public function create(): View
    {
        $vehicles = Vehicle::orderBy('registration_number')->get();
        return view('fleet.fuel-logs.create', compact('vehicles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(['vehicle_id' => 'required|exists:vehicles,id', 'trip_id' => 'nullable|exists:trips,id', 'fuel_type' => 'required|string', 'liters' => 'required|numeric|min:0', 'cost_per_liter' => 'required|numeric|min:0', 'odometer_reading' => 'required|numeric|min:0', 'filled_at' => 'required|date', 'station_name' => 'nullable|string']);
        $vehicle = Vehicle::find($validated['vehicle_id']);
        $this->fleetService->logFuel($vehicle, $validated);
        return redirect()->route('fleet.fuel-logs.index')->with('success', 'Fuel log added.');
    }

    public function show(FuelLog $fuelLog): View
    {
        $fuelLog->load(['vehicle', 'trip']);
        return view('fleet.fuel-logs.show', compact('fuelLog'));
    }

    public function edit(FuelLog $fuelLog): View
    {
        $vehicles = Vehicle::orderBy('registration_number')->get();
        return view('fleet.fuel-logs.edit', compact('fuelLog', 'vehicles'));
    }

    public function update(Request $request, FuelLog $fuelLog): RedirectResponse
    {
        $fuelLog->update($request->only(['liters', 'cost_per_liter', 'odometer_reading', 'station_name']));
        return redirect()->route('fleet.fuel-logs.index')->with('success', 'Fuel log updated.');
    }

    public function destroy(FuelLog $fuelLog): RedirectResponse
    {
        $fuelLog->delete();
        return redirect()->route('fleet.fuel-logs.index')->with('success', 'Fuel log deleted.');
    }
}
