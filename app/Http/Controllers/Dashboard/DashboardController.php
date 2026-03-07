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
        $stats = $spendChart = $spendCurrentYear = $spendPrevYear = [];
        $recentRequisitions = collect();
        $pendingInvoices = collect();
        $spendByVendor = collect();
        $spendByCategory = collect();
        $poStatusDistribution = collect();
        $recentActivity = [];
        $spendChange = null;

        if ($user->hasRole('admin')) {
            $stats = $this->dashboardService->getAdminStats();
            $recentRequisitions = PurchaseRequisition::with('requester')->latest()->limit(5)->get();
            $pendingInvoices = Invoice::with('vendor')->where('status', 'pending')->latest()->limit(5)->get();
            $spendChart = $this->dashboardService->getSpendByMonth(12);
            $spendByVendor = $this->reportService->getSpendByVendor()->take(5);
            $spendByCategory = $this->reportService->getSpendByCategory()->take(5);
            $poStatusDistribution = $this->reportService->getPoStatusDistribution();
            $recentActivity = $this->dashboardService->getRecentActivity(8);
            $spendCurrentYear = $this->dashboardService->getSpendByMonthForYear(now()->year);
            $spendPrevYear = $this->dashboardService->getSpendByMonthForYear(now()->year - 1);
            $spendChange = $this->computeSpendChange($spendChart);
        } elseif ($user->hasRole('manager')) {
            $stats = $this->dashboardService->getManagerStats();
            $recentRequisitions = PurchaseRequisition::with('requester')->latest()->limit(5)->get();
            $pendingInvoices = Invoice::with('vendor')->where('status', 'pending')->latest()->limit(5)->get();
            $spendChart = $this->dashboardService->getSpendByMonth(12);
            $spendByVendor = $this->reportService->getSpendByVendor()->take(5);
            $spendByCategory = $this->reportService->getSpendByCategory()->take(5);
            $poStatusDistribution = $this->reportService->getPoStatusDistribution();
            $recentActivity = $this->dashboardService->getRecentActivity(8);
            $spendCurrentYear = $this->dashboardService->getSpendByMonthForYear(now()->year);
            $spendPrevYear = $this->dashboardService->getSpendByMonthForYear(now()->year - 1);
            $spendChange = $this->computeSpendChange($spendChart);
        } elseif ($user->hasRole('buyer')) {
            $stats = $this->dashboardService->getBuyerStats();
            $recentRequisitions = PurchaseRequisition::where('requested_by', $user->id)->latest()->limit(5)->get();
        } elseif ($user->hasRole('warehouse_worker')) {
            $stats = $this->dashboardService->getWarehouseStats();
        }

        return view('dashboard', compact(
            'stats', 'recentRequisitions', 'pendingInvoices', 'spendChart',
            'spendByVendor', 'spendByCategory', 'poStatusDistribution',
            'recentActivity', 'spendCurrentYear', 'spendPrevYear', 'spendChange'
        ));
    }

    private function computeSpendChange(array $spendChart): ?float
    {
        if (count($spendChart) < 2) {
            return null;
        }
        $prev = $spendChart[count($spendChart) - 2]['total'];
        $curr = $spendChart[count($spendChart) - 1]['total'];
        return $prev > 0 ? round(($curr - $prev) / $prev * 100, 1) : 0;
    }
}
