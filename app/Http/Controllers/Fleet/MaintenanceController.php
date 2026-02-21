<?php
namespace App\Http\Controllers\Fleet;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRecord;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function index(): View
    {
        $records = MaintenanceRecord::with('vehicle')->latest('scheduled_date')->paginate(15);
        return view('fleet.maintenance.index', compact('records'));
    }

    public function create(): View
    {
        $vehicles = Vehicle::orderBy('registration_number')->get();
        return view('fleet.maintenance.create', compact('vehicles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $record = MaintenanceRecord::create($request->validate(['vehicle_id' => 'required|exists:vehicles,id', 'type' => 'required|string', 'description' => 'required|string', 'scheduled_date' => 'required|date', 'cost' => 'nullable|numeric', 'performed_by' => 'nullable|string', 'notes' => 'nullable|string']));
        return redirect()->route('fleet.maintenance.show', $record)->with('success', 'Maintenance scheduled.');
    }

    public function show(MaintenanceRecord $maintenance): View
    {
        $maintenance->load('vehicle');
        return view('fleet.maintenance.show', compact('maintenance'));
    }

    public function edit(MaintenanceRecord $maintenance): View
    {
        $vehicles = Vehicle::orderBy('registration_number')->get();
        return view('fleet.maintenance.edit', compact('maintenance', 'vehicles'));
    }

    public function update(Request $request, MaintenanceRecord $maintenance): RedirectResponse
    {
        $maintenance->update($request->validate(['type' => 'required|string', 'description' => 'required|string', 'scheduled_date' => 'required|date', 'completed_date' => 'nullable|date', 'cost' => 'nullable|numeric', 'performed_by' => 'nullable|string', 'notes' => 'nullable|string']));
        return redirect()->route('fleet.maintenance.show', $maintenance)->with('success', 'Maintenance updated.');
    }

    public function destroy(MaintenanceRecord $maintenance): RedirectResponse
    {
        $maintenance->delete();
        return redirect()->route('fleet.maintenance.index')->with('success', 'Record deleted.');
    }
}
