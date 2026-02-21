<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\PurchaseRequisition;
use App\Services\DashboardService;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService,
        private readonly ReportService $reportService,
    ) {}

    public function index(): View
    {
        $user = auth()->user();
        $stats = $spendChart = [];
        $recentRequisitions = collect();
        $pendingInvoices = collect();
        $spendByVendor = collect();
        $spendByCategory = collect();
        $poStatusDistribution = collect();

        if ($user->hasRole('admin')) {
            $stats = $this->dashboardService->getAdminStats();
            $recentRequisitions = PurchaseRequisition::with('requester')->latest()->limit(5)->get();
            $pendingInvoices = Invoice::with('vendor')->where('status', 'pending')->latest()->limit(5)->get();
            $spendChart = $this->dashboardService->getSpendByMonth(6);
            $spendByVendor = $this->reportService->getSpendByVendor()->take(5);
            $spendByCategory = $this->reportService->getSpendByCategory()->take(5);
            $poStatusDistribution = $this->reportService->getPoStatusDistribution();
        } elseif ($user->hasRole('manager')) {
            $stats = $this->dashboardService->getManagerStats();
            $recentRequisitions = PurchaseRequisition::with('requester')->latest()->limit(5)->get();
            $pendingInvoices = Invoice::with('vendor')->where('status', 'pending')->latest()->limit(5)->get();
            $spendChart = $this->dashboardService->getSpendByMonth(6);
            $spendByVendor = $this->reportService->getSpendByVendor()->take(5);
            $spendByCategory = $this->reportService->getSpendByCategory()->take(5);
            $poStatusDistribution = $this->reportService->getPoStatusDistribution();
        } elseif ($user->hasRole('buyer')) {
            $stats = $this->dashboardService->getBuyerStats();
            $recentRequisitions = PurchaseRequisition::where('requested_by', $user->id)->latest()->limit(5)->get();
        } elseif ($user->hasRole('warehouse_worker')) {
            $stats = $this->dashboardService->getWarehouseStats();
        }

        return view('dashboard', compact(
            'stats', 'recentRequisitions', 'pendingInvoices', 'spendChart',
            'spendByVendor', 'spendByCategory', 'poStatusDistribution'
        ));
    }
}
