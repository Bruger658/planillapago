<?php

use App\Http\Controllers\FinanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FinanceController::class, 'index'])->name('finances.index');
Route::post('/mes', [FinanceController::class, 'storeBudget'])->name('finances.budget.store');
Route::post('/mes/{monthlyBudget}/gastos', [FinanceController::class, 'storeExpense'])->name('finances.expenses.store');
Route::get('/gastos/{expense}/editar', [FinanceController::class, 'editExpense'])->name('finances.expenses.edit');
Route::put('/gastos/{expense}', [FinanceController::class, 'updateExpense'])->name('finances.expenses.update');
Route::delete('/gastos/{expense}', [FinanceController::class, 'destroyExpense'])->name('finances.expenses.destroy');