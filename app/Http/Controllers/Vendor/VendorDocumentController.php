<?php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VendorDocumentController extends Controller
{
    public function store(Request $request, Vendor $vendor): RedirectResponse
    {
        $request->validate(['document' => 'required|file|max:10240', 'type' => 'required|string|max:100']);
        $path = $request->file('document')->store("vendor-docs/{$vendor->id}", 'local');
        $vendor->documents()->create([
            'type' => $request->type,
            'file_name' => $request->file('document')->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $request->file('document')->getSize(),
            'mime_type' => $request->file('document')->getMimeType(),
            'uploaded_by' => auth()->id(),
        ]);
        return redirect()->route('vendors.show', $vendor)->with('success', 'Document uploaded.');
    }

    public function destroy(Vendor $vendor, VendorDocument $document): RedirectResponse
    {
        Storage::disk('local')->delete($document->file_path);
        $document->delete();
        return redirect()->route('vendors.show', $vendor)->with('success', 'Document deleted.');
    }

    public function download(Vendor $vendor, VendorDocument $document)
    {
        return Storage::disk('local')->download($document->file_path, $document->file_name);
    }
}
