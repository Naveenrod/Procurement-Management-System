<?php
namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BudgetController extends Controller
{
    public function index(): View
    {
        $budgets = Budget::with('category')->orderBy('fiscal_year', 'desc')->paginate(15);
        return view('procurement.budgets.index', compact('budgets'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('procurement.budgets.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'department' => 'required|string|max:255',
            'fiscal_year' => 'required|integer|min:2020|max:2030',
            'category_id' => 'nullable|exists:categories,id',
            'allocated_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        $validated['remaining_amount'] = $validated['allocated_amount'];
        $budget = Budget::create($validated);
        return redirect()->route('procurement.budgets.show', $budget)->with('success', 'Budget created.');
    }

    public function show(Budget $budget): View
    {
        return view('procurement.budgets.show', compact('budget'));
    }

    public function edit(Budget $budget): View
    {
        $categories = Category::orderBy('name')->get();
        return view('procurement.budgets.edit', compact('budget', 'categories'));
    }

    public function update(Request $request, Budget $budget): RedirectResponse
    {
        $budget->update($request->validate(['department' => 'required|string', 'fiscal_year' => 'required|integer', 'allocated_amount' => 'required|numeric|min:0', 'notes' => 'nullable|string']));
        return redirect()->route('procurement.budgets.show', $budget)->with('success', 'Budget updated.');
    }

    public function destroy(Budget $budget): RedirectResponse
    {
        $budget->delete();
        return redirect()->route('procurement.budgets.index')->with('success', 'Budget deleted.');
    }
}
