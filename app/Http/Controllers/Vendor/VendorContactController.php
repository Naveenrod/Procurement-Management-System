<?php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorContact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VendorContactController extends Controller
{
    public function store(Request $request, Vendor $vendor): RedirectResponse
    {
        $vendor->contacts()->create($request->validate(['name' => 'required|string', 'position' => 'nullable|string', 'email' => 'nullable|email', 'phone' => 'nullable|string', 'is_primary' => 'boolean']));
        return redirect()->route('vendors.show', $vendor)->with('success', 'Contact added.');
    }

    public function update(Request $request, Vendor $vendor, VendorContact $contact): RedirectResponse
    {
        $contact->update($request->validate(['name' => 'required|string', 'position' => 'nullable|string', 'email' => 'nullable|email', 'phone' => 'nullable|string']));
        return redirect()->route('vendors.show', $vendor)->with('success', 'Contact updated.');
    }

    public function destroy(Vendor $vendor, VendorContact $contact): RedirectResponse
    {
        $contact->delete();
        return redirect()->route('vendors.show', $vendor)->with('success', 'Contact removed.');
    }
}
