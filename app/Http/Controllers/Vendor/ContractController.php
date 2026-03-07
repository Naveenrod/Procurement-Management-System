<?php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Vendor;
use App\Enums\ContractStatus;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContractController extends Controller
{
    public function index(Request $request): View
    {
        $contracts = Contract::with('vendor')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()->paginate(15);
        return view('contracts.index', compact('contracts'));
    }

    public function create(): View
    {
        $vendors = Vendor::where('status', 'approved')->get();
        return view('contracts.create', compact('vendors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(['vendor_id' => 'required|exists:vendors,id', 'title' => 'required|string', 'value' => 'required|numeric|min:0', 'start_date' => 'required|date', 'end_date' => 'required|date|after:start_date', 'terms' => 'nullable|string', 'notes' => 'nullable|string']);
        $validated['status'] = ContractStatus::Draft->value;
        $validated['created_by'] = auth()->id();
        $contract = Contract::create($validated);
        return redirect()->route('contracts.show', $contract)->with('success', 'Contract created.');
    }

    public function show(Contract $contract): View
    {
        $contract->load('vendor');
        return view('contracts.show', compact('contract'));
    }

    public function edit(Contract $contract): View
    {
        $vendors = Vendor::where('status', 'approved')->get();
        return view('contracts.edit', compact('contract', 'vendors'));
    }

    public function update(Request $request, Contract $contract): RedirectResponse
    {
        $contract->update($request->validate(['title' => 'required|string', 'value' => 'required|numeric', 'start_date' => 'required|date', 'end_date' => 'required|date', 'terms' => 'nullable|string', 'notes' => 'nullable|string']));
        return redirect()->route('contracts.show', $contract)->with('success', 'Contract updated.');
    }

    public function destroy(Contract $contract): RedirectResponse
    {
        $contract->delete();
        return redirect()->route('contracts.index')->with('success', 'Contract deleted.');
    }

    public function exportPdf(Contract $contract): \Illuminate\Http\Response
    {
        $contract->load(['vendor', 'creator', 'approver']);
        $pdf = Pdf::loadView('contracts.pdf', compact('contract'));
        return $pdf->download($contract->contract_number . '.pdf');
    }

    public function approve(Contract $contract): RedirectResponse
    {
        $contract->update(['status' => ContractStatus::Active->value, 'approved_by' => auth()->id()]);
        return redirect()->route('contracts.show', $contract)->with('success', 'Contract activated.');
    }

    public function terminate(Contract $contract): RedirectResponse
    {
        $contract->update(['status' => ContractStatus::Terminated->value]);
        return redirect()->route('contracts.show', $contract)->with('success', 'Contract terminated.');
    }
}
