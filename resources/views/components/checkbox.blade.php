@props(['value' => ''])

<input type="checkbox"
    {{ $attributes->merge(['class' => 'rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300']) }}
    value="{{ $value }}">
