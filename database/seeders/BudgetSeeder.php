<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Budget;

class BudgetSeeder extends Seeder
{
    public function run(): void
    {
        $year = (int) date('Y');
        $budgets = [
            ['department' => 'IT', 'allocated_amount' => 150000, 'spent_amount' => 78500],
            ['department' => 'Finance', 'allocated_amount' => 50000, 'spent_amount' => 22300],
            ['department' => 'Operations', 'allocated_amount' => 200000, 'spent_amount' => 145000],
            ['department' => 'HR', 'allocated_amount' => 30000, 'spent_amount' => 12000],
            ['department' => 'Admin', 'allocated_amount' => 25000, 'spent_amount' => 8500],
            ['department' => 'Warehouse', 'allocated_amount' => 80000, 'spent_amount' => 45000],
        ];

        foreach ($budgets as $data) {
            Budget::firstOrCreate(
                ['department' => $data['department'], 'fiscal_year' => $year],
                array_merge($data, [
                    'fiscal_year' => $year,
                    'remaining_amount' => $data['allocated_amount'] - $data['spent_amount'],
                ])
            );
        }
    }
}
