<div class="py-4 border-t bg-slate-800 bg-opacity-90 text-white text-opacity-80 border-white border-opacity-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row flex-wrap items-center justify-between">
            <div class="flex items-center gap-2 text-xs">
                <span class="font-medium">{{ config('app.name') }}</span>
                <span>•</span>
                <span>© {{ date('Y') }} {{ __('footer.rights_reserved') }}</span>
            </div>
            
            <div class="flex items-center gap-4 text-xs mt-3 sm:mt-0">
                <!-- <a href="{{ route('changelog') }}" class="text-cyan-400 transition-colors">{{ __('footer.changelog') }}</a> -->
                <a href="{{ route('terms') }}" class="text-cyan-400 transition-colors">{{ __('footer.terms') }}</a>
                <a href="{{ route('privacy') }}" class="text-cyan-400 transition-colors">{{ __('footer.privacy') }}</a>
            </div>
        </div>
    </div>
</div>