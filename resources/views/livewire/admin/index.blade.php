<div class="container max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 admin-container">
    <!-- Navigation tabs -->
    <div class="flex mb-6 gap-4 text-center">
        <a href="{{ route('admin.users') }}" class="flex-1 px-5 py-3 rounded-lg text-white bg-cyan-600 hover:bg-cyan-500 transition-colors">
            <i class="fas fa-users mr-2"></i> Users
        </a>
        <a href="{{ route('admin.visits') }}" class="flex-1 px-5 py-3 rounded-lg text-white bg-cyan-600 hover:bg-cyan-500 transition-colors">
            <i class="fas fa-chart-line mr-2"></i> Visits
        </a>
    </div>

    <!-- Welcome message -->
    <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl shadow-lg overflow-hidden p-8 text-center">
        <h2 class="text-2xl font-bold text-white mb-4">Administration Area</h2>
        <p class="text-cyan-200 mb-6">Select one of the options above to manage users or view visit statistics.</p>
        
        <div class="flex justify-center space-x-6">
            <div class="text-center">
                <div class="text-4xl font-bold text-amber-300 mb-2">{{ \App\Models\User::count() }}</div>
                <div class="text-sm text-cyan-200">Registered Users</div>
            </div>
            
            <div class="text-center">
                <div class="text-4xl font-bold text-amber-300 mb-2">{{ \App\Models\Visit::count() }}</div>
                <div class="text-sm text-cyan-200">Recorded Visits</div>
            </div>
            
            <div class="text-center">
                <div class="text-4xl font-bold text-amber-300 mb-2">{{ \App\Models\Visit::distinct('ip_address')->count('ip_address') }}</div>
                <div class="text-sm text-cyan-200">Unique Visitors</div>
            </div>
        </div>
    </div>
</div>