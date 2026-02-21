<?php
namespace App\Http\Controllers\Fleet;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DriverController extends Controller
{
    public function index(): View
    {
        $drivers = Driver::latest()->paginate(15);
        return view('fleet.drivers.index', compact('drivers'));
    }

    public function create(): View { return view('fleet.drivers.create'); }

    public function store(Request $request): RedirectResponse
    {
        $driver = Driver::create($request->validate(['name' => 'required|string', 'phone' => 'required|string', 'license_number' => 'required|string|unique:drivers', 'license_expiry' => 'required|date']));
        return redirect()->route('fleet.drivers.show', $driver)->with('success', 'Driver added.');
    }

    public function show(Driver $driver): View
    {
        $driver->load(['trips' => fn($q) => $q->latest()->limit(10)]);
        return view('fleet.drivers.show', compact('driver'));
    }

    public function edit(Driver $driver): View { return view('fleet.drivers.edit', compact('driver')); }

    public function update(Request $request, Driver $driver): RedirectResponse
    {
        $driver->update($request->validate(['name' => 'required|string', 'phone' => 'required|string', 'license_number' => 'required|string|unique:drivers,license_number,' . $driver->id, 'license_expiry' => 'required|date']));
        return redirect()->route('fleet.drivers.show', $driver)->with('success', 'Driver updated.');
    }

    public function destroy(Driver $driver): RedirectResponse
    {
        $driver->delete();
        return redirect()->route('fleet.drivers.index')->with('success', 'Driver removed.');
    }
}
