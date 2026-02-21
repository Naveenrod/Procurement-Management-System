<?php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Services\VendorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorPerformanceController extends Controller
{
    public function __construct(private readonly VendorService $vendorService) {}

    public function index(Vendor $vendor): View
    {
        $scores = $vendor->performanceScores()->latest()->paginate(10);
        return view('vendors.performance', compact('vendor', 'scores'));
    }

    public function create(Vendor $vendor): View { return view('vendors.performance-create', compact('vendor')); }

    public function store(Request $request, Vendor $vendor): RedirectResponse
    {
        $validated = $request->validate(['period_start' => 'required|date', 'period_end' => 'required|date|after:period_start', 'delivery_score' => 'required|numeric|min:0|max:100', 'quality_score' => 'required|numeric|min:0|max:100', 'price_score' => 'required|numeric|min:0|max:100', 'responsiveness_score' => 'required|numeric|min:0|max:100', 'notes' => 'nullable|string']);
        $this->vendorService->recordPerformanceScore($vendor, $validated);
        return redirect()->route('vendors.performance.index', $vendor)->with('success', 'Performance score recorded.');
    }
}
