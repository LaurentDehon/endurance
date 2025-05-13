<div class="section-card bg-slate-900 bg-opacity-90 border-white border-opacity-20 border backdrop-blur-lg rounded-xl p-6 shadow-lg">
    <div class="flex items-center gap-4 mb-6">
        <div class="w-12 h-12 rounded-full text-white bg-cyan-600 hover:bg-cyan-500 flex items-center justify-center">
            <i class="fas fa-envelope text-xl text-white"></i>
        </div>
        <h2 class="text-2xl font-bold text-white">{{ __('admin.modal.email.title', ['name' => $user->name]) }}</h2>
    </div>
    
    <form wire:submit.prevent="sendEmail" class="mt-8 space-y-6">
        <div class="rounded-md space-y-4">
            <div>
                <label for="subject" class="block text-sm font-medium text-white mb-1">{{ __('admin.modal.email.subject') }}</label>
                <input id="subject" wire:model="email.subject" type="text" required placeholder="{{ __('admin.modal.email.subject_placeholder') }}" 
                    class="input w-full h-10 px-4 py-2 rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 placeholder-gray-400">
                @error('email.subject') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="message" class="block text-sm font-medium text-white mb-1">{{ __('admin.modal.email.message') }}</label>
                <textarea id="message" wire:model="email.message" rows="6" required placeholder="{{ __('admin.modal.email.message_placeholder') }}" 
                    class="input w-full px-4 py-2 rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 placeholder-gray-400"></textarea>
                @error('email.message') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>
        </div>
        
        <div class="flex justify-end gap-3">
            <button type="button" wire:click="$dispatch('closeModal')" 
                class="py-2 px-4 text-sm font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-500">
                {{ __('admin.modal.email.cancel') }}
            </button>
            <button type="submit" 
                class="group relative py-2 px-4 text-sm font-medium rounded-md bg-amber-600 text-white hover:bg-amber-500">
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <i class="fas fa-paper-plane"></i>
                </span>
                <span class="pl-6">{{ __('admin.modal.email.send') }}</span>
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
