<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\StoreMonthlyBudgetRequest;
use App\Models\Expense;
use App\Models\MonthlyBudget;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class FinanceController extends Controller
{
    public function index(): View
    {
        $monthlyBudget = MonthlyBudget::query()
            ->with(['expenses' => fn ($query) => $query->latest('spent_on')->latest()])
            ->latest('month')
            ->first();

        $expensesTotal = $monthlyBudget?->expenses->sum('amount') ?? 0;
        $availableTotal = $monthlyBudget
            ? $monthlyBudget->previous_surplus + $monthlyBudget->retirement_income + $monthlyBudget->pension_income
            : 0;

        return view('finances.index', [
            'monthlyBudget' => $monthlyBudget,
            'expensesTotal' => $expensesTotal,
            'availableTotal' => $availableTotal,
            'remainingTotal' => $availableTotal - $expensesTotal,
            'currentMonth' => now()->startOfMonth()->toDateString(),
        ]);
    }

    public function storeBudget(StoreMonthlyBudgetRequest $request): RedirectResponse
    {
        MonthlyBudget::updateOrCreate(
            ['month' => Carbon::parse($request->validated('month'))->startOfMonth()],
            $request->safe()->except('month'),
        );

        return redirect()->route('finances.index')->with('status', 'Mes guardado correctamente.');
    }

    public function storeExpense(StoreExpenseRequest $request, MonthlyBudget $monthlyBudget): RedirectResponse
    {
        $monthlyBudget->expenses()->create($request->validated());

        return redirect()->route('finances.index')->with('status', 'Gasto agregado correctamente.');
    }

    public function editExpense(Expense $expense): View
    {
        return view('finances.edit-expense', ['expense' => $expense]);
    }

    public function updateExpense(StoreExpenseRequest $request, Expense $expense): RedirectResponse
    {
        $expense->update($request->validated());

        return redirect()->route('finances.index')->with('status', 'Gasto actualizado correctamente.');
    }

    public function destroyExpense(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return redirect()->route('finances.index')->with('status', 'Gasto eliminado correctamente.');
    }
}