<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(private readonly ReportService $reportService) {}

    public function index(): View { return view('reports.index'); }

    public function procurement(Request $request): View
    {
        $filters = $request->only(['from', 'to']);
        $data = $this->reportService->getProcurementReport($filters);
        $spendByVendor = $this->reportService->getSpendByVendor();
        $spendByCategory = $this->reportService->getSpendByCategory();
        $spendByMonth = $this->reportService->getSpendByMonth(12);
        return view('reports.procurement', compact('data', 'spendByVendor', 'spendByCategory', 'spendByMonth', 'filters'));
    }

    public function inventory(Request $request): View
    {
        $data = $this->reportService->getInventoryReport($request->only(['from', 'to']));
        return view('reports.inventory', compact('data'));
    }

    public function vendor(Request $request): View
    {
        $data = $this->reportService->getVendorReport($request->only(['from', 'to']));
        return view('reports.vendor', compact('data'));
    }

    public function fleet(Request $request): View
    {
        $data = $this->reportService->getFleetReport($request->only(['from', 'to']));
        return view('reports.fleet', compact('data'));
    }
}
