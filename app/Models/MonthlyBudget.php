<?php

namespace App\Models;

use Database\Factories\MonthlyBudgetFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['month', 'previous_surplus', 'retirement_income', 'pension_income', 'notes'])]
class MonthlyBudget extends Model
{
    /** @use HasFactory<MonthlyBudgetFactory> */
    use HasFactory;

    /**
     * @return HasMany<Expense, $this>
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'month' => 'date:Y-m-d',
            'previous_surplus' => 'decimal:2',
            'retirement_income' => 'decimal:2',
            'pension_income' => 'decimal:2',
        ];
    }
}