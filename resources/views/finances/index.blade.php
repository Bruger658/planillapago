<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gastos de jubilación y pensión</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <main class="mx-auto flex w-full max-w-6xl flex-col gap-8 px-4 py-8 sm:px-6 lg:px-8">
        <header class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Planilla familiar</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight">Gastos de jubilación y pensión</h1>
            <p class="mt-3 max-w-3xl text-slate-600">Cargá el sobrante del mes anterior, sumá jubilación y pensión, y registrá cada gasto para ver cuánto queda disponible.</p>
        </header>

        @if (session('status'))
            <div class="rounded-2xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 ring-1 ring-emerald-200">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl bg-red-50 px-4 py-3 text-sm text-red-800 ring-1 ring-red-200">
                <p class="font-semibold">Revisá los datos:</p>
                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="grid grid-cols-1 gap-4 md:grid-cols-3">
           <article class="rounded-2xl bg-slate-900 p-5 shadow-xl shadow-slate-950/30 ring-1 ring-slate-700">
                <p class="text-sm text-slate-400">Disponible del mes</p>
                <p class="mt-2 text-2xl font-bold text-emerald-300">$ {{ number_format($availableTotal, 2, ',', '.') }}</p>
            </article>
            <article class="rounded-2xl bg-slate-900 p-5 shadow-xl shadow-slate-950/30 ring-1 ring-slate-700">
                <p class="text-sm text-slate-400">Gastos cargados</p>
                <p class="mt-2 text-2xl font-bold text-red-300">$ {{ number_format($expensesTotal, 2, ',', '.') }}</p>
            </article>
            <article class="rounded-2xl bg-slate-900 p-5 shadow-xl shadow-slate-950/30 ring-1 ring-slate-700">
                <p class="text-sm text-slate-400">Saldo restante</p>
                <p class="mt-2 text-2xl font-bold {{ $remainingTotal >= 0 ? 'text-blue-300' : 'text-red-300' }}">$ {{ number_format($remainingTotal, 2, ',', '.') }}</p>
            </article>
        </section>

        <section class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <form method="POST" action="{{ route('finances.budget.store') }}" class="rounded-3xl bg-slate-900 p-6 shadow-xl shadow-slate-950/30 ring-1 ring-slate-700">
                @csrf
                <h2 class="text-xl font-semibold">Datos del mes</h2>
                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <label class="flex flex-col gap-2 text-sm font-medium">Mes
                        <input class="rounded-xl border border-slate-600 bg-slate-950 px-3 py-2 text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30" type="date" name="month" value="{{ old('month', $monthlyBudget?->month?->toDateString() ?? $currentMonth) }}" required>
                    </label>
                    <label class="flex flex-col gap-2 text-sm font-medium">Sobrante anterior
                        <input class="rounded-xl border border-slate-600 bg-slate-950 px-3 py-2 text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30" type="number" name="previous_surplus" step="0.01" min="0" value="{{ old('previous_surplus', $monthlyBudget?->previous_surplus ?? 0) }}" required>
                    </label>
                    <label class="flex flex-col gap-2 text-sm font-medium">Jubilación
                        <input class="rounded-xl border border-slate-600 bg-slate-950 px-3 py-2 text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30" type="number" name="retirement_income" step="0.01" min="0" value="{{ old('retirement_income', $monthlyBudget?->retirement_income ?? 0) }}" required>
                    </label>
                    <label class="flex flex-col gap-2 text-sm font-medium">Pensión
                         <input class="rounded-xl border border-slate-600 bg-slate-950 px-3 py-2 text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30" type="number" name="pension_income" step="0.01" min="0" value="{{ old('pension_income', $monthlyBudget?->pension_income ?? 0) }}" required>
                    </label>
                </div>
                <label class="mt-4 flex flex-col gap-2 text-sm font-medium">Notas
                    <textarea class="rounded-xl border border-slate-600 bg-slate-950 px-3 py-2 text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30" name="notes" rows="3">{{ old('notes', $monthlyBudget?->notes) }}</textarea>
                </label>
                <button class="mt-5 rounded-xl bg-emerald-700 px-4 py-2 font-semibold text-white hover:bg-emerald-800" type="submit">Guardar mes</button>
            </form>

             <form method="POST" action="{{ $monthlyBudget ? route('finances.expenses.store', $monthlyBudget) : '#' }}" class="rounded-3xl bg-slate-900 p-6 shadow-xl shadow-slate-950/30 ring-1 ring-slate-700">
                @csrf
                <h2 class="text-xl font-semibold">Agregar gasto</h2>
                @unless ($monthlyBudget)
                    <p class="mt-4 rounded-xl bg-amber-950 p-3 text-sm text-amber-100">Primero guardá los datos del mes para poder agregar gastos.</p>
                @endunless
                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <label class="flex flex-col gap-2 text-sm font-medium sm:col-span-2">Descripción
                       <input class="rounded-xl border border-slate-600 bg-slate-950 px-3 py-2 text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30" type="text" name="description" value="{{ old('description') }}" required @disabled(! $monthlyBudget)>
                    </label>
                    <label class="flex flex-col gap-2 text-sm font-medium">Importe
                        <input class="rounded-xl border border-slate-600 bg-slate-950 px-3 py-2 text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30" type="number" name="amount" step="0.01" min="0.01" value="{{ old('amount') }}" required @disabled(! $monthlyBudget)>
                    </label>
                    <label class="flex flex-col gap-2 text-sm font-medium">Fecha
                        <input class="rounded-xl border border-slate-600 bg-slate-950 px-3 py-2 text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30" type="date" name="spent_on" value="{{ old('spent_on', now()->toDateString()) }}" required @disabled(! $monthlyBudget)>
                    </label>
                    <label class="flex flex-col gap-2 text-sm font-medium sm:col-span-2">Categoría
                        <input class="rounded-xl border border-slate-600 bg-slate-950 px-3 py-2 text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30" type="text" name="category" value="{{ old('category') }}" @disabled(! $monthlyBudget)>
                    </label>
                </div>
                <button class="mt-5 rounded-xl bg-blue-700 px-4 py-2 font-semibold text-white hover:bg-blue-800 disabled:cursor-not-allowed disabled:bg-slate-600" type="submit" @disabled(! $monthlyBudget)>Agregar gasto</button>
            </form>
        </section>

        <section class="overflow-hidden rounded-3xl bg-slate-900 shadow-xl shadow-slate-950/30 ring-1 ring-slate-700">
            <div class="border-b border-slate-700 p-6">
                <h2 class="text-xl font-semibold">Gastos registrados</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-700 text-sm">
                    <thead class="bg-slate-800 text-left text-slate-300">
                        <tr>
                            <th class="px-6 py-3">Fecha</th>
                            <th class="px-6 py-3">Descripción</th>
                            <th class="px-6 py-3">Categoría</th>
                            <th class="px-6 py-3 text-right">Importe</th>
                            <th class="px-6 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @forelse ($monthlyBudget?->expenses ?? [] as $expense)
                            <tr>
                                <td class="px-6 py-4">{{ $expense->spent_on->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 font-medium">{{ $expense->description }}</td>
                                <td class="px-6 py-4">{{ $expense->category ?: 'Sin categoría' }}</td>
                                <td class="px-6 py-4 text-right font-semibold">$ {{ number_format($expense->amount, 2, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-3">
                                        <a class="font-semibold text-blue-300 hover:text-blue-100" href="{{ route('finances.expenses.edit', $expense) }}">Editar</a>
                                        <form method="POST" action="{{ route('finances.expenses.destroy', $expense) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="font-semibold text-red-300 hover:text-red-100" type="submit">Borrar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td class="px-6 py-8 text-center text-slate-400" colspan="5">Todavía no hay gastos cargados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>