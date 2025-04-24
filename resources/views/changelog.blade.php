@extends('layouts.app')

@section('title', 'Changelog & Roadmap')

@section('content')
<div class="changelog-container mx-auto p-4 md:p-8 max-w-2xl">
    <!-- Header Section -->
    <div class="bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl shadow-lg mb-8 py-6 border">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">Changelog & Roadmap</h1>
            <p class="text-lg md:text-xl text-cyan-200 max-w-2xl mx-auto mb-4">
                Track our progress and see what's coming next
            </p>
        </div>
    </div>

    <!-- Navigation Pills -->
    <div class="mb-8 p-3 bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm rounded-lg border">
        <div class="flex flex-wrap justify-center gap-2">
            <a href="#changelog" class="whitespace-nowrap px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-history"></i> Recent Updates
            </a>
            <a href="#roadmap" class="whitespace-nowrap px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-road"></i> Upcoming Features
            </a>
        </div>
    </div>
    
    <!-- Changelog Section -->
    <section id="changelog" class="section-card bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center">
                <i class="fas fa-history text-xl"></i>
            </div>
            <h2 class="text-2xl md:text-3xl font-bold text-white">Recent Updates</h2>
        </div>
        
        <div class="space-y-6">
            @foreach($changelog as $release)
                <div class="bg-white bg-opacity-5 rounded-lg overflow-hidden p-4 border border-white border-opacity-10 transition-all duration-300 hover:transform hover:scale-[1.01] hover:shadow-md">
                    <div class="flex flex-wrap items-baseline justify-between mb-4 pb-2 border-b border-white border-opacity-10">
                        <h3 class="text-xl font-medium text-white">{{ $release['date'] }}</h3>
                    </div>
                    <ul class="space-y-2 text-cyan-200">
                        @foreach($release['changes'] as $change)
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-green-400 mt-1 flex-shrink-0"></i>
                                <span>{{ $change }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </section>
    
    <!-- Roadmap Section -->
    <section id="roadmap" class="section-card bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center">
                <i class="fas fa-road text-xl"></i>
            </div>
            <h2 class="text-2xl md:text-3xl font-bold text-white">Upcoming Features</h2>
        </div>
        
        <div class="space-y-6">
            @foreach($roadmap as $feature)
                <div class="bg-white bg-opacity-5 rounded-lg overflow-hidden p-4 border border-white border-opacity-10 transition-all duration-300 hover:transform hover:scale-[1.01] hover:shadow-md">
                    <ul class="space-y-2 text-cyan-200">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-star text-amber-400 mt-1 flex-shrink-0"></i>
                            <span>{{ $feature }}</span>
                        </li>
                    </ul>
                </div>
            @endforeach
        </div>
    </section>
</div>

<style>
    html, body {
        height: 100%;
        overflow-y: auto;
        margin: 0;
        padding: 0;
    }
    
    .changelog-container {
        overflow-y: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    
    .changelog-container::-webkit-scrollbar {
        display: none;
    }
    
    .section-card {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .section-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transform: translateX(-100%);
        transition: transform 1s ease;
    }
    
    .section-card:hover::after {
        transform: translateX(100%);
    }
    
    .section-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection