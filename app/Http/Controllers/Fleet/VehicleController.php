<?php
namespace App\Http\Controllers\Fleet;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function index(): View
    {
        $vehicles = Vehicle::latest()->paginate(15);
        return view('fleet.vehicles.index', compact('vehicles'));
    }

    public function create(): View { return view('fleet.vehicles.create'); }

    public function store(Request $request): RedirectResponse
    {
        $vehicle = Vehicle::create($request->validate(['registration_number' => 'required|string|unique:vehicles', 'make' => 'required|string', 'model' => 'required|string', 'year' => 'required|integer|min:1990|max:' . (date('Y') + 1), 'type' => 'required|string', 'fuel_type' => 'required|string', 'insurance_expiry' => 'nullable|date', 'registration_expiry' => 'nullable|date']));
        return redirect()->route('fleet.vehicles.show', $vehicle)->with('success', 'Vehicle added.');
    }

    public function show(Vehicle $vehicle): View
    {
        $vehicle->load(['trips' => fn($q) => $q->latest()->limit(5), 'maintenanceRecords' => fn($q) => $q->latest()->limit(5), 'fuelLogs' => fn($q) => $q->latest()->limit(5)]);
        return view('fleet.vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle): View { return view('fleet.vehicles.edit', compact('vehicle')); }

    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $vehicle->update($request->validate(['make' => 'required|string', 'model' => 'required|string', 'year' => 'required|integer', 'type' => 'required|string', 'fuel_type' => 'required|string', 'insurance_expiry' => 'nullable|date', 'registration_expiry' => 'nullable|date']));
        return redirect()->route('fleet.vehicles.show', $vehicle)->with('success', 'Vehicle updated.');
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $vehicle->delete();
        return redirect()->route('fleet.vehicles.index')->with('success', 'Vehicle removed.');
    }
}
