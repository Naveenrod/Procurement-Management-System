<?php
namespace App\Http\Controllers\Fleet;

use App\Http\Controllers\Controller;
use App\Models\FleetRoute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RouteController extends Controller
{
    public function index(): View
    {
        $routes = FleetRoute::latest()->paginate(15);
        return view('fleet.routes.index', compact('routes'));
    }

    public function create(): View { return view('fleet.routes.create'); }

    public function store(Request $request): RedirectResponse
    {
        $route = FleetRoute::create($request->validate(['name' => 'required|string', 'origin' => 'required|string', 'destination' => 'required|string', 'distance_km' => 'required|numeric|min:0', 'estimated_hours' => 'nullable|numeric|min:0']));
        return redirect()->route('fleet.routes.show', $route)->with('success', 'Route created.');
    }

    public function show(FleetRoute $route): View { return view('fleet.routes.show', compact('route')); }
    public function edit(FleetRoute $route): View { return view('fleet.routes.edit', compact('route')); }

    public function update(Request $request, FleetRoute $route): RedirectResponse
    {
        $route->update($request->validate(['name' => 'required|string', 'origin' => 'required|string', 'destination' => 'required|string', 'distance_km' => 'required|numeric', 'estimated_hours' => 'nullable|numeric']));
        return redirect()->route('fleet.routes.show', $route)->with('success', 'Route updated.');
    }

    public function destroy(FleetRoute $route): RedirectResponse
    {
        $route->delete();
        return redirect()->route('fleet.routes.index')->with('success', 'Route deleted.');
    }
}
