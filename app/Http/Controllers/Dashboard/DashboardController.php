<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboardService) {}

    public function index(): View
    {
        $user = auth()->user();
        $stats = $recentActivity = $spendChart = [];

        if ($user->hasRole('admin')) {
            $stats = $this->dashboardService->getAdminStats();
            $recentActivity = $this->dashboardService->getRecentActivity(10);
            $spendChart = $this->dashboardService->getSpendByMonth(6);
        } elseif ($user->hasRole('manager')) {
            $stats = $this->dashboardService->getManagerStats();
            $recentActivity = $this->dashboardService->getRecentActivity(10);
            $spendChart = $this->dashboardService->getSpendByMonth(6);
        } elseif ($user->hasRole('buyer')) {
            $stats = $this->dashboardService->getBuyerStats();
        } elseif ($user->hasRole('warehouse_worker')) {
            $stats = $this->dashboardService->getWarehouseStats();
        }

        return view('dashboard', compact('stats', 'recentActivity', 'spendChart'));
    }
}
