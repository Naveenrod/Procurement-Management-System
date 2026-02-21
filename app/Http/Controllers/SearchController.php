<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Vendor;
use App\Models\PurchaseOrder;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->get('q', '');
        $results = ['products' => collect(), 'vendors' => collect(), 'purchase_orders' => collect(), 'invoices' => collect()];

        if (strlen($query) >= 2) {
            $results['products'] = Product::where('name', 'like', "%{$query}%")->orWhere('sku', 'like', "%{$query}%")->limit(10)->get();
            $results['vendors'] = Vendor::where('name', 'like', "%{$query}%")->orWhere('code', 'like', "%{$query}%")->limit(10)->get();
            $results['purchase_orders'] = PurchaseOrder::where('po_number', 'like', "%{$query}%")->limit(10)->get();
            $results['invoices'] = Invoice::where('invoice_number', 'like', "%{$query}%")->limit(10)->get();
        }

        return view('search.results', compact('query', 'results'));
    }
}
