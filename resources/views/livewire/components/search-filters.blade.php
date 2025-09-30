<!-- Search Filters Component -->
<!-- City Filter -->
<div x-data="{ cityOpen: false }" class="relative">
    <label class="block text-xs font-medium text-slate-700 dark:text-slate-200 mb-1">Grad/Mesto</label>
    <button type="button" @click="cityOpen = !cityOpen"
        class="w-full flex justify-between items-center border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 text-left text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white dark:bg-slate-600">
        <span class="{{ $city ? 'text-slate-900 dark:text-slate-100' : 'text-slate-500 dark:text-slate-300' }}">
            {{ $city ?: 'Svi gradovi' }}
        </span>
        <svg class="w-4 h-4 transition-transform" :class="cityOpen ? 'rotate-180' : ''" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="cityOpen" x-transition @click.away="cityOpen = false"
        class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-md shadow-lg max-h-60 overflow-y-auto">
        <div class="p-1">
            <button type="button" wire:click="$set('city', '')" @click="cityOpen = false"
                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-slate-100 dark:hover:bg-slate-600 transition {{ !$city ? 'bg-slate-100 dark:bg-slate-600 text-sky-700 dark:text-sky-400 font-medium' : 'text-slate-700 dark:text-slate-200' }}">
                Svi gradovi
            </button>
            @foreach(config('cities', []) as $cityOption)
                <button type="button" wire:click="$set('city', '{{ $cityOption }}')" @click="cityOpen = false"
                    class="w-full text-left px-3 py-2 text-sm rounded hover:bg-slate-100 dark:hover:bg-slate-600 transition {{ $city === $cityOption ? 'bg-slate-100 dark:bg-slate-600 text-sky-700 dark:text-sky-400 font-medium' : 'text-slate-700 dark:text-slate-200' }}">
                    {{ $cityOption }}
                </button>
            @endforeach
        </div>
    </div>
</div>

<!-- Price From -->
<div>
    <label class="block text-xs font-medium text-slate-700 dark:text-slate-200 mb-1">Cena od</label>
    <input type="number" wire:model.live.debounce.500ms="price_min" placeholder="0"
        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-600 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
</div>

<!-- Price To -->
<div>
    <label class="block text-xs font-medium text-slate-700 dark:text-slate-200 mb-1">Cena do</label>
    <input type="number" wire:model.live.debounce.500ms="price_max" placeholder="∞"
        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-600 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
</div>

<!-- Condition Filter -->
<div x-data="{ conditionOpen: false }" class="relative">
    <label class="block text-xs font-medium text-slate-700 dark:text-slate-200 mb-1">Stanje</label>
    <button type="button" @click="conditionOpen = !conditionOpen"
        class="w-full flex justify-between items-center border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 text-left text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white dark:bg-slate-600">
        <span class="{{ $condition_id ? 'text-slate-900 dark:text-slate-100' : 'text-slate-500 dark:text-slate-300' }}">
            @if($condition_id)
                {{ $conditions->firstWhere('id', $condition_id)->name ?? 'Sva stanja' }}
            @else
                Sva stanja
            @endif
        </span>
        <svg class="w-4 h-4 transition-transform" :class="conditionOpen ? 'rotate-180' : ''" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="conditionOpen" x-transition @click.away="conditionOpen = false"
        class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-md shadow-lg max-h-60 overflow-y-auto">
        <div class="p-1">
            <button type="button" wire:click="$set('condition_id', '')" @click="conditionOpen = false"
                class="w-full text-left px-3 py-2 text-sm rounded hover:bg-slate-100 dark:hover:bg-slate-600 transition {{ !$condition_id ? 'bg-slate-100 dark:bg-slate-600 text-sky-700 dark:text-sky-400 font-medium' : 'text-slate-700 dark:text-slate-200' }}">
                Sva stanja
            </button>
            @foreach($conditions as $condition)
                <button type="button" wire:click="$set('condition_id', '{{ $condition->id }}')" @click="conditionOpen = false"
                    class="w-full text-left px-3 py-2 text-sm rounded hover:bg-slate-100 dark:hover:bg-slate-600 transition {{ $condition_id == $condition->id ? 'bg-slate-100 dark:bg-slate-600 text-sky-700 dark:text-sky-400 font-medium' : 'text-slate-700 dark:text-slate-200' }}">
                    {{ $condition->name }}
                </button>
            @endforeach
        </div>
    </div>
</div>
