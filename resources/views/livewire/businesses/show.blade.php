<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Business Header -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">
                <!-- Logo Section -->
                @if($business->logo)
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 p-8">
                        <div class="flex justify-center">
                            <img src="{{ Storage::url($business->logo) }}"
                                 alt="{{ $business->name }}"
                                 class="max-h-32 w-auto object-contain">
                        </div>
                    </div>
                @endif

                <!-- Business Info -->
                <div class="p-6">
                    <!-- Title and Category -->
                    <div class="mb-4">
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-2">
                            {{ $business->name }}
                        </h1>

                        @if($business->slogan)
                            <p class="text-lg text-purple-600 dark:text-purple-400 italic mb-3">
                                "{{ $business->slogan }}"
                            </p>
                        @endif

                        <!-- Category Badge -->
                        <div class="flex flex-wrap gap-2 mb-3">
                            @if($business->category)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                    @if($business->category->icon)
                                        <i class="{{ $business->category->icon }} mr-1"></i>
                                    @endif
                                    {{ $business->category->name }}
                                </span>
                            @endif

                            @if($business->subcategory)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                    {{ $business->subcategory->name }}
                                </span>
                            @endif
                        </div>

                        <!-- Meta Info -->
                        <div class="flex flex-wrap items-center gap-4 text-sm text-slate-600 dark:text-slate-400">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $business->location }}
                            </div>
                            @if($business->established_year)
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Posluje od {{ $business->established_year }}
                                </div>
                            @endif
                            <div class="flex items-center">
                                <i class="fas fa-eye mr-1"></i>
                                {{ $business->views }} pregleda
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $business->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="prose dark:prose-invert max-w-none mb-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">O nama</h3>
                        <p class="text-slate-700 dark:text-slate-300 whitespace-pre-line">{{ $business->description }}</p>
                    </div>

                    <!-- Social Links -->
                    @if($business->website_url || $business->facebook_url || $business->instagram_url || $business->youtube_url)
                        <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Pratite nas</h3>
                            <div class="flex flex-wrap gap-3">
                                @if($business->website_url)
                                    <a href="{{ $business->website_url }}" target="_blank" rel="noopener"
                                       class="inline-flex items-center px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                                        <i class="fas fa-globe mr-2"></i>
                                        Website
                                    </a>
                                @endif
                                @if($business->facebook_url)
                                    <a href="{{ $business->facebook_url }}" target="_blank" rel="noopener"
                                       class="inline-flex items-center px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                                        <i class="fab fa-facebook mr-2"></i>
                                        Facebook
                                    </a>
                                @endif
                                @if($business->instagram_url)
                                    <a href="{{ $business->instagram_url }}" target="_blank" rel="noopener"
                                       class="inline-flex items-center px-4 py-2 bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-300 rounded-lg hover:bg-pink-200 dark:hover:bg-pink-900/50 transition-colors">
                                        <i class="fab fa-instagram mr-2"></i>
                                        Instagram
                                    </a>
                                @endif
                                @if($business->youtube_url)
                                    <a href="{{ $business->youtube_url }}" target="_blank" rel="noopener"
                                       class="inline-flex items-center px-4 py-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                                        <i class="fab fa-youtube mr-2"></i>
                                        YouTube
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Images Gallery -->
            @if($business->images->count() > 0)
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Galerija</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($business->images as $image)
                            <div class="aspect-square overflow-hidden rounded-lg">
                                <img src="{{ Storage::url($image->path) }}"
                                     alt="{{ $business->name }}"
                                     class="w-full h-full object-cover hover:scale-110 transition-transform duration-300 cursor-pointer">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Contact Card -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 sticky top-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                    <i class="fas fa-address-card mr-2 text-purple-600"></i>
                    Kontakt informacije
                </h3>

                <div class="space-y-4">
                    <!-- Primary Contact -->
                    @if($business->contact_phone)
                        <div class="flex items-start">
                            <i class="fas fa-phone text-purple-600 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Telefon</p>
                                <a href="tel:{{ $business->contact_phone }}"
                                   class="text-slate-900 dark:text-slate-100 hover:text-purple-600 dark:hover:text-purple-400">
                                    {{ $business->contact_phone }}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($business->contact_email)
                        <div class="flex items-start">
                            <i class="fas fa-envelope text-purple-600 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Email</p>
                                <a href="mailto:{{ $business->contact_email }}"
                                   class="text-slate-900 dark:text-slate-100 hover:text-purple-600 dark:hover:text-purple-400 break-all">
                                    {{ $business->contact_email }}
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Additional Contacts -->
                    @if($business->contact_phone_2 && $business->contact_name_2)
                        <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                            <div class="flex items-start">
                                <i class="fas fa-phone text-purple-600 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $business->contact_name_2 }}</p>
                                    <a href="tel:{{ $business->contact_phone_2 }}"
                                       class="text-slate-900 dark:text-slate-100 hover:text-purple-600 dark:hover:text-purple-400">
                                        {{ $business->contact_phone_2 }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($business->contact_phone_3 && $business->contact_name_3)
                        <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                            <div class="flex items-start">
                                <i class="fas fa-phone text-purple-600 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $business->contact_name_3 }}</p>
                                    <a href="tel:{{ $business->contact_phone_3 }}"
                                       class="text-slate-900 dark:text-slate-100 hover:text-purple-600 dark:hover:text-purple-400">
                                        {{ $business->contact_phone_3 }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Owner Info -->
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">Objavio</p>
                        <div class="flex items-center">
                            @if($business->user->avatar)
                                <img src="{{ $business->user->avatar_url }}"
                                     alt="{{ $business->user->name }}"
                                     class="w-10 h-10 rounded-full mr-3">
                            @else
                                <div class="w-10 h-10 rounded-full bg-purple-500 flex items-center justify-center text-white font-medium mr-3">
                                    {{ strtoupper(substr($business->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-slate-900 dark:text-slate-100 font-medium">{{ $business->user->name }}</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Član od {{ $business->user->created_at->format('Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @auth
                        @if(auth()->id() === $business->user_id)
                            <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                                <a href="{{ route('businesses.edit', $business->slug) }}"
                                   class="block w-full text-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                    <i class="fas fa-edit mr-2"></i>
                                    Izmeni
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Share Card -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                    <i class="fas fa-share-alt mr-2 text-purple-600"></i>
                    Podeli
                </h3>
                <div class="flex gap-2">
                    <button onclick="shareBusiness()"
                            class="flex-1 px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                        <i class="fas fa-share mr-2"></i>
                        Podeli
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function shareBusiness() {
        const url = window.location.href;
        const title = {{ Js::from($business->name) }};
        const text = 'Pogledaj ovaj biznis: ' + title;

        // Check if mobile and has native share
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

        if (navigator.share && isMobile) {
            // Use native share on mobile
            navigator.share({
                title: title,
                text: text,
                url: url
            }).catch(err => {
                // If share fails, show popup
                showSharePopup(url, title, text);
            });
        } else {
            // Desktop - show popup
            showSharePopup(url, title, text);
        }
    }

    function showSharePopup(url, title, text) {
        // Remove existing popup if any
        const existing = document.getElementById('sharePopup');
        if (existing) existing.remove();

        // Create popup container
        const overlay = document.createElement('div');
        overlay.id = 'sharePopup';
        overlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';

        // Create popup content
        const popup = document.createElement('div');
        popup.className = 'bg-white dark:bg-slate-800 rounded-lg p-6 max-w-md w-full mx-4';

        // Header
        const header = document.createElement('div');
        header.className = 'flex justify-between items-center mb-4';

        const heading = document.createElement('h3');
        heading.className = 'text-lg font-semibold text-slate-800 dark:text-slate-200';
        heading.textContent = 'Podeli biznis';

        const closeBtn = document.createElement('button');
        closeBtn.className = 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200';
        closeBtn.innerHTML = '<i class="fas fa-times"></i>';
        closeBtn.onclick = () => overlay.remove();

        header.appendChild(heading);
        header.appendChild(closeBtn);

        // Share options container
        const options = document.createElement('div');
        options.className = 'space-y-3';

        // Facebook
        const fbLink = document.createElement('a');
        fbLink.href = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url);
        fbLink.target = '_blank';
        fbLink.className = 'flex items-center p-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors';
        fbLink.innerHTML = '<i class="fab fa-facebook-f w-6"></i><span class="ml-3">Podeli na Facebook-u</span>';

        // WhatsApp
        const waLink = document.createElement('a');
        waLink.href = 'https://wa.me/?text=' + encodeURIComponent(text + ' ' + url);
        waLink.target = '_blank';
        waLink.className = 'flex items-center p-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors';
        waLink.innerHTML = '<i class="fab fa-whatsapp w-6"></i><span class="ml-3">Podeli na WhatsApp-u</span>';

        // Email
        const emailLink = document.createElement('a');
        emailLink.href = 'mailto:?subject=' + encodeURIComponent(title) + '&body=' + encodeURIComponent(text + '\n\n' + url);
        emailLink.className = 'flex items-center p-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors';
        emailLink.innerHTML = '<i class="fas fa-envelope w-6"></i><span class="ml-3">Pošalji Email</span>';

        // Copy link button
        const copyBtn = document.createElement('button');
        copyBtn.className = 'w-full flex items-center p-3 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors';
        copyBtn.innerHTML = '<i class="fas fa-link w-6"></i><span class="ml-3">Kopiraj link</span>';
        copyBtn.onclick = () => {
            copyToClipboard(url);
            overlay.remove();
        };

        // Add all options
        options.appendChild(fbLink);
        options.appendChild(waLink);
        options.appendChild(emailLink);
        options.appendChild(copyBtn);

        // Assemble popup
        popup.appendChild(header);
        popup.appendChild(options);
        overlay.appendChild(popup);

        // Close on overlay click
        overlay.onclick = (e) => {
            if (e.target === overlay) overlay.remove();
        };

        document.body.appendChild(overlay);
    }

    function copyToClipboard(url) {
        navigator.clipboard.writeText(url).then(() => {
            // Show success message
            const message = document.createElement('div');
            message.className = 'fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center';

            const icon = document.createElement('i');
            icon.className = 'fas fa-check mr-2';

            const text = document.createElement('span');
            text.textContent = 'Link je kopiran u clipboard!';

            message.appendChild(icon);
            message.appendChild(text);
            document.body.appendChild(message);

            setTimeout(() => {
                message.remove();
            }, 3000);
        }).catch(err => {
            alert('Greška pri kopiranju linka');
        });
    }
</script>
