<?php
namespace App\Http\Controllers\SupplierPortal;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\View\View;

class SupplierPerformanceController extends Controller
{
    public function index(): View
    {
        $vendor = Vendor::with(['performanceScores' => fn($q) => $q->latest()->limit(8)])->find(auth()->user()->vendor_id);
        $scores = $vendor?->performanceScores ?? collect();
        return view('supplier.performance.index', compact('vendor', 'scores'));
    }
}
