<?php
namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SpendAnalysisController extends Controller
{
    public function __construct(private readonly ReportService $reportService) {}

    public function index(): View
    {
        $spendByCategory = $this->reportService->getSpendByCategory();
        $spendByVendor = $this->reportService->getSpendByVendor();
        $spendByMonth = $this->reportService->getSpendByMonth(12);
        return view('procurement.spend-analysis', compact('spendByCategory', 'spendByVendor', 'spendByMonth'));
    }
}
