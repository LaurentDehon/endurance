<div class="section-card {{ themeClass('modal-bg') }} border backdrop-blur-lg rounded-xl p-6 shadow-lg">
    <div class="flex items-center gap-4 mb-6">
        <div class="w-12 h-12 rounded-full {{ themeClass('button') }} flex items-center justify-center">
            <i class="fas fa-envelope text-xl {{ themeClass('text-1') }}"></i>
        </div>
        <h2 class="text-2xl font-bold {{ themeClass('text-1') }}">Send Email to {{ $user->name }}</h2>
    </div>
    
    <form wire:submit.prevent="sendEmail" class="mt-8 space-y-6">
        <div class="rounded-md space-y-4">
            <div>
                <label for="subject" class="block text-sm font-medium {{ themeClass('text-1') }} mb-1">Subject</label>
                <input id="subject" wire:model="email.subject" type="text" required placeholder="Subject of your message" 
                    class="input w-full h-10 px-4 py-2 rounded-lg {{ themeClass('input') }} placeholder-gray-400">
                @error('email.subject') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="message" class="block text-sm font-medium {{ themeClass('text-1') }} mb-1">Your Message</label>
                <textarea id="message" wire:model="email.message" rows="6" required placeholder="Your message..." 
                    class="input w-full px-4 py-2 rounded-lg {{ themeClass('input') }} placeholder-gray-400"></textarea>
                @error('email.message') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>
        </div>
        
        <div class="flex justify-end gap-3">
            <button type="button" wire:click="$dispatch('closeModal')" 
                class="py-2 px-4 text-sm font-medium rounded-md {{ themeClass('button') }}">
                Cancel
            </button>
            <button type="submit" 
                class="group relative py-2 px-4 text-sm font-medium rounded-md {{ themeClass('button-accent') }}">
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <i class="fas fa-paper-plane"></i>
                </span>
                <span class="pl-6">Send Email</span>
            </button>
        </div>
    </form>
    <style>
        .input {
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
        }
        
        .input:focus, .input:active, .input:hover {
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
            border-width: 0 !important;
            border-color: transparent !important;
            ring-width: 0 !important;
        }
    </style>
</div>
