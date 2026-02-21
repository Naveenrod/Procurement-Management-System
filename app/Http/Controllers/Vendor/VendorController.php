<?php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Services\VendorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorController extends Controller
{
    public function __construct(private readonly VendorService $vendorService) {}

    public function index(Request $request): View
    {
        $vendors = Vendor::query()
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->search, fn($q, $s) => $q->where('name', 'like', "%{$s}%")->orWhere('code', 'like', "%{$s}%"))
            ->latest()->paginate(15);
        return view('vendors.index', compact('vendors'));
    }

    public function create(): View { return view('vendors.create'); }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|url',
            'tax_id' => 'nullable|string|max:50',
            'payment_terms' => 'nullable|string|max:255',
        ]);
        $vendor = $this->vendorService->createVendor($validated);
        return redirect()->route('vendors.show', $vendor)->with('success', 'Vendor created successfully.');
    }

    public function show(Vendor $vendor): View
    {
        $vendor->load(['contacts', 'documents', 'performanceScores' => fn($q) => $q->latest()->limit(4)]);
        return view('vendors.show', compact('vendor'));
    }

    public function edit(Vendor $vendor): View { return view('vendors.edit', compact('vendor')); }

    public function update(Request $request, Vendor $vendor): RedirectResponse
    {
        $vendor->update($request->validate(['name' => 'required|string', 'contact_person' => 'required|string', 'email' => 'required|email|unique:vendors,email,' . $vendor->id, 'phone' => 'nullable|string', 'address' => 'nullable|string', 'city' => 'nullable|string', 'country' => 'nullable|string', 'website' => 'nullable|url', 'tax_id' => 'nullable|string', 'payment_terms' => 'nullable|string']));
        return redirect()->route('vendors.show', $vendor)->with('success', 'Vendor updated.');
    }

    public function destroy(Vendor $vendor): RedirectResponse
    {
        $vendor->delete();
        return redirect()->route('vendors.index')->with('success', 'Vendor deleted.');
    }

    public function approve(Vendor $vendor): RedirectResponse
    {
        $this->vendorService->approveVendor($vendor);
        return redirect()->route('vendors.show', $vendor)->with('success', 'Vendor approved.');
    }

    public function suspend(Vendor $vendor): RedirectResponse
    {
        $this->vendorService->suspendVendor($vendor);
        return redirect()->route('vendors.show', $vendor)->with('success', 'Vendor suspended.');
    }
}
