<?php
namespace App\Http\Controllers\SupplierPortal;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierProfileController extends Controller
{
    public function edit(): View
    {
        $vendor = Vendor::findOrFail(auth()->user()->vendor_id);
        return view('supplier.profile.edit', compact('vendor'));
    }

    public function update(Request $request): RedirectResponse
    {
        $vendor = Vendor::findOrFail(auth()->user()->vendor_id);
        $vendor->update($request->validate(['phone' => 'nullable|string|max:50', 'address' => 'nullable|string', 'city' => 'nullable|string', 'country' => 'nullable|string', 'website' => 'nullable|url', 'notes' => 'nullable|string']));
        return redirect()->route('supplier.profile.edit')->with('success', 'Profile updated.');
    }
}
