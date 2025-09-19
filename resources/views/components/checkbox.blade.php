@props(['value' => ''])

<input type="checkbox"
    {{ $attributes->merge(['class' => 'rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-slate-300']) }}
    value="{{ $value }}">
