<div class="container max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 admin-container">
    <!-- Navigation tabs -->
    <div class="flex mb-6 gap-4 text-center">
        <a href="{{ route('admin.users') }}" class="flex-1 px-5 py-3 rounded-lg text-white bg-cyan-600 hover:bg-cyan-500 transition-colors">
            <i class="fas fa-users mr-2"></i> {{ __('admin.navigation.users') }}
        </a>
        <a href="{{ route('admin.visits') }}" class="flex-1 px-5 py-3 rounded-lg text-white bg-cyan-600 hover:bg-cyan-500 transition-colors">
            <i class="fas fa-chart-line mr-2"></i> {{ __('admin.navigation.visits') }}
        </a>
    </div>

    <!-- Welcome message -->
    <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl shadow-lg overflow-hidden p-8 text-center">
        <h2 class="text-2xl font-bold text-white mb-4">{{ __('admin.header.title') }}</h2>
        <p class="text-cyan-200 mb-6">{{ __('admin.header.subtitle') }}</p>
        
        <div class="flex justify-center space-x-6">
            <div class="text-center">
                <div class="text-4xl font-bold text-amber-300 mb-2">{{ \App\Models\User::count() }}</div>
                <div class="text-sm text-cyan-200">{{ __('admin.stats.registered_users') }}</div>
            </div>
            
            <div class="text-center">
                <div class="text-4xl font-bold text-amber-300 mb-2">{{ \App\Models\Visit::count() }}</div>
                <div class="text-sm text-cyan-200">{{ __('admin.stats.recorded_visits') }}</div>
            </div>
            
            <div class="text-center">
                <div class="text-4xl font-bold text-amber-300 mb-2">{{ \App\Models\Visit::distinct('ip_address')->count('ip_address') }}</div>
                <div class="text-sm text-cyan-200">{{ __('admin.stats.unique_visitors') }}</div>
            </div>
        </div>
    </div>
</div>