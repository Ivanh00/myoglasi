<div>
    @if(!$showEmailSent)
        <!-- Magic Link Form -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
            <div class="flex items-center mb-3">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-magic text-blue-600"></i>
                </div>
                <h3 class="text-sm font-medium text-blue-900">Brza prijava preko email-a</h3>
            </div>
            
            <p class="text-xs text-blue-700 mb-3">
                Unesite email adresu i dobićete link za automatsku prijavu. Ako nemate nalog, kreaće se automatski.
            </p>
            
            <form wire:submit.prevent="sendMagicLink" class="space-y-3">
                <div>
                    <input type="email" wire:model="email" placeholder="unesite@email.com"
                           class="w-full px-3 py-2 border border-blue-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" wire:loading.attr="disabled"
                        class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 transition-colors">
                    <span wire:loading.remove>
                        <i class="fas fa-paper-plane mr-1"></i>
                        Pošalji magic link
                    </span>
                    <span wire:loading>
                        <i class="fas fa-spinner fa-spin mr-1"></i>
                        Šaljem...
                    </span>
                </button>
            </form>
        </div>
    @else
        <!-- Email Sent Confirmation -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
            <div class="flex items-center mb-3">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-check text-green-600"></i>
                </div>
                <h3 class="text-sm font-medium text-green-900">Magic link poslat!</h3>
            </div>
            
            <p class="text-xs text-green-700 mb-3">
                Proverite email (<strong>{{ $email }}</strong>) i kliknite na link da se prijavite.
            </p>
            
            <div class="flex space-x-2">
                <button wire:click="sendMagicLink" 
                        class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                    <i class="fas fa-redo mr-1"></i>
                    Pošalji ponovo
                </button>
                <button wire:click="resetForm" 
                        class="px-3 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Nazad
                </button>
            </div>
        </div>
    @endif

    <!-- Error Messages -->
    @if(session()->has('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                <span class="text-red-700 text-sm">{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>