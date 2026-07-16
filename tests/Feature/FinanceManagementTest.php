<?php

use App\Models\Expense;
use App\Models\MonthlyBudget;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

it('shows the monthly budget totals', function () {
    $monthlyBudget = MonthlyBudget::create([
        'month' => '2026-07-01',
        'previous_surplus' => 10000,
        'retirement_income' => 250000,
        'pension_income' => 150000,
    ]);

    $monthlyBudget->expenses()->create([
        'description' => 'Farmacia',
        'amount' => 20000,
        'spent_on' => '2026-07-10',
        'category' => 'Salud',
    ]);

    $this->get(route('finances.index'))
        ->assertSuccessful()
        ->assertSee('Gastos de jubilación y pensión')
        ->assertSee('$ 410.000,00')
        ->assertSee('$ 20.000,00')
        ->assertSee('$ 390.000,00')
        ->assertSee('Farmacia');
});

it('can create update and delete an expense', function () {
    $monthlyBudget = MonthlyBudget::create([
        'month' => '2026-07-01',
        'previous_surplus' => 0,
        'retirement_income' => 250000,
        'pension_income' => 150000,
    ]);

    $this->post(route('finances.expenses.store', $monthlyBudget), [
        'description' => 'Supermercado',
        'amount' => 35000,
        'spent_on' => '2026-07-11',
        'category' => 'Comida',
    ])->assertRedirect(route('finances.index'));

    $expense = Expense::firstOrFail();

    $this->put(route('finances.expenses.update', $expense), [
        'description' => 'Verdulería',
        'amount' => 12000,
        'spent_on' => '2026-07-12',
        'category' => 'Alimentos',
    ])->assertRedirect(route('finances.index'));

    expect($expense->fresh())
        ->description->toBe('Verdulería')
        ->amount->toBe('12000.00');

    $this->delete(route('finances.expenses.destroy', $expense))
        ->assertRedirect(route('finances.index'));

    $this->assertModelMissing($expense);
});