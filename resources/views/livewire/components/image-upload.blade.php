@props(['images' => [], 'maxImages' => 10, 'wireModel' => 'tempImages'])

<!-- Images Upload -->
<div>
    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
        Slike (maksimalno {{ $maxImages }})
        @if (!empty($images))
            <span class="text-sky-600 dark:text-sky-400">({{ count($images) }}/{{ $maxImages }})</span>
        @endif
    </label>

    <!-- Upload Area -->
    @if (count($images ?? []) < $maxImages)
        <div
            class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg p-6 text-center hover:border-slate-400 dark:hover:border-slate-500 transition-colors">
            <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                </path>
            </svg>
            <input type="file" wire:model="{{ $wireModel }}" multiple accept="image/*" class="hidden"
                id="image-upload-{{ $wireModel }}">
            <label for="image-upload-{{ $wireModel }}" class="cursor-pointer">
                <span class="text-sky-600 hover:text-sky-500 dark:text-sky-400 dark:hover:text-sky-300 font-medium">
                    Kliknite za dodavanje slika
                </span>
                <span class="text-slate-500 dark:text-slate-300"> ili prevucite ovde</span>
            </label>
            <p class="text-slate-400 text-sm mt-2">PNG, JPG, JPEG do 5MB po slici</p>
        </div>
    @else
        <div
            class="border-2 border-slate-300 dark:border-slate-600 rounded-lg p-6 text-center bg-slate-50 dark:bg-slate-700">
            <i class="fas fa-images text-slate-400 text-4xl mb-2"></i>
            <p class="text-slate-600 dark:text-slate-400 font-medium">Dostigli ste maksimum od {{ $maxImages }}
                slika</p>
            <p class="text-slate-500 dark:text-slate-300 text-sm">Obrišite neku sliku da biste dodali novu</p>
        </div>
    @endif

    @error('images')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
    @error('images.*')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror

    <!-- Image Previews -->
    @if (!empty($images))
        <div class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach ($images as $index => $image)
                <div class="relative group">
                    <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                        class="w-full h-24 object-cover rounded-lg border border-slate-300 dark:border-slate-600">
                    <button type="button" wire:click="removeImage({{ $index }})"
                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
                        ×
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</div>
