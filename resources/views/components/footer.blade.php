<div class="py-4 border-t bg-slate-800 bg-opacity-90 text-white text-opacity-80 border-white border-opacity-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-xs">
                <span class="font-medium">{{ config('app.name') }}</span>
                <span>•</span>
                <span>© {{ date('Y') }} All rights reserved</span>
            </div>
            
            <div class="flex items-center gap-4 text-xs mt-3 sm:mt-0">
                <a href="{{ route('changelog') }}" class="text-cyan-400 transition-colors">Changelog</a>
                <a href="{{ route('terms') }}" class="text-cyan-400 transition-colors">Terms</a>
                <a href="{{ route('privacy') }}" class="text-cyan-400 transition-colors">Privacy</a>
            </div>
        </div>
    </div>
</div>