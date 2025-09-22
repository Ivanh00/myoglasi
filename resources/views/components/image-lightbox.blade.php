@props(['images' => [], 'title' => ''])

<div x-data="{
    lightboxOpen: false,
    currentIndex: 0,
    images: {{ json_encode($images) }},
    openLightbox(index) {
        this.currentIndex = index || 0;
        this.lightboxOpen = true;
        document.body.style.overflow = 'hidden';
    },
    closeLightbox() {
        this.lightboxOpen = false;
        document.body.style.overflow = '';
    },
    nextImage() {
        this.currentIndex = (this.currentIndex + 1) % this.images.length;
    },
    prevImage() {
        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
    }
}"
x-on:keydown.escape.window="closeLightbox()"
x-on:keydown.arrow-right.window="lightboxOpen && nextImage()"
x-on:keydown.arrow-left.window="lightboxOpen && prevImage()">

    <!-- Lightbox Modal -->
    <div x-show="lightboxOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[9999] flex items-center justify-center"
         style="display: none;">

        <!-- Background overlay -->
        <div @click="closeLightbox()"
             class="absolute inset-0 bg-black bg-opacity-95"></div>

        <!-- Close button -->
        <button @click="closeLightbox()"
                class="absolute top-4 right-4 text-white hover:text-gray-300 z-[10001] transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Previous button -->
        <button @click="prevImage()"
                x-show="images.length > 1"
                class="absolute left-4 top-1/2 -translate-y-1/2 text-white hover:text-gray-300 z-[10001] transition-colors p-2 bg-black bg-opacity-50 rounded-full">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <!-- Next button -->
        <button @click="nextImage()"
                x-show="images.length > 1"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-white hover:text-gray-300 z-[10001] transition-colors p-2 bg-black bg-opacity-50 rounded-full">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

        <!-- Main image container -->
        <div class="relative z-[10000] max-w-[90vw] max-h-[90vh]">
            <img :src="images[currentIndex]?.url || images[currentIndex]"
                 :alt="images[currentIndex]?.alt || '{{ $title }}'"
                 class="max-w-full max-h-[90vh] object-contain"
                 @click.stop>

            <!-- Image counter -->
            <div x-show="images.length > 1"
                 class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-black bg-opacity-75 text-white px-3 py-1 rounded-full text-sm">
                <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
            </div>
        </div>

        <!-- Thumbnail strip (optional, for many images) -->
        <div x-show="images.length > 1"
             class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-2 max-w-[80vw] overflow-x-auto z-[10001] p-2 bg-black bg-opacity-50 rounded-lg"
             style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.3) transparent;">
            <template x-for="(image, index) in images" :key="index">
                <button @click="currentIndex = index"
                        :class="{'ring-2 ring-white': currentIndex === index}"
                        class="flex-shrink-0 w-16 h-16 rounded overflow-hidden transition-all hover:opacity-100"
                        :style="currentIndex === index ? 'opacity: 1' : 'opacity: 0.6'">
                    <img :src="image.url || image"
                         :alt="image.alt || ''"
                         class="w-full h-full object-cover">
                </button>
            </template>
        </div>
    </div>

    <!-- Slot for the trigger content -->
    {{ $slot }}
</div>