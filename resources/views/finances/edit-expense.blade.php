<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar gasto</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <main class="mx-auto flex min-h-screen w-full max-w-2xl items-center px-4 py-8">
        <form method="POST" action="{{ route('finances.expenses.update', $expense) }}" class="w-full rounded-3xl bg-slate-900 p-6 shadow-xl shadow-slate-950/30 ring-1 ring-slate-700">
            @csrf
            @method('PUT')
            <a class="text-sm font-semibold text-blue-300 hover:text-blue-100" href="{{ route('finances.index') }}">← Volver</a>
            <h1 class="mt-4 text-2xl font-bold">Editar gasto</h1>

            @if ($errors->any())
                <div class="mt-5 rounded-2xl bg-red-950 px-4 py-3 text-sm text-red-100 ring-1 ring-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <label class="flex flex-col gap-2 text-sm font-medium sm:col-span-2">Descripción
                    <input class="rounded-xl border border-slate-600 bg-slate-950 px-3 py-2 text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30" type="text" name="description" value="{{ old('description', $expense->description) }}" required>
                </label>
                <label class="flex flex-col gap-2 text-sm font-medium">Importe
                    <input class="rounded-xl border border-slate-600 bg-slate-950 px-3 py-2 text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30" type="number" name="amount" step="0.01" min="0.01" value="{{ old('amount', $expense->amount) }}" required>
                </label>
                <label class="flex flex-col gap-2 text-sm font-medium">Fecha
                    <input class="rounded-xl border border-slate-600 bg-slate-950 px-3 py-2 text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30" type="date" name="spent_on" value="{{ old('spent_on', $expense->spent_on->toDateString()) }}" required>
                </label>
                <label class="flex flex-col gap-2 text-sm font-medium sm:col-span-2">Categoría
                    <input class="rounded-xl border border-slate-600 bg-slate-950 px-3 py-2 text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30" type="text" name="category" value="{{ old('category', $expense->category) }}">
                </label>
            </div>

            <div class="mt-6 flex gap-3">
                <button class="rounded-xl bg-blue-700 px-4 py-2 font-semibold text-white hover:bg-blue-800" type="submit">Guardar cambios</button>
                 <a class="rounded-xl border border-slate-600 px-4 py-2 font-semibold text-slate-200 hover:bg-slate-800" href="{{ route('finances.index') }}">Cancelar</a>
            </div>
        </form>
    </main>
</body>
</html>