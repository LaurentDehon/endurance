@extends('layouts.app')

@section('content')
<style>
    html, body {
        height: 100%;
        overflow-y: hidden !important;
        margin: 0;
        padding: 0;
    }
    
    .dashboard-content-container {
        overflow-y: auto;
        scrollbar-width: none; /* Masque la scrollbar sur Firefox */
        -ms-overflow-style: none; /* Masque la scrollbar sur IE/Edge */
    }
    
    .dashboard-content-container::-webkit-scrollbar {
        width: 0;
        display: none; /* Masque la scrollbar sur Chrome, Safari et Opera */
    }
</style>

<div class="dashboard-content-container">
    <div class="container mx-auto px-4 py-12">
        <div class="{{ themeClass('card-bg', 'bg-white bg-opacity-10') }} backdrop-blur-lg rounded-xl p-6 shadow-xl border {{ themeClass('card-border', 'border-white border-opacity-20') }}">
            <div class="flex flex-col items-center justify-center py-8">
                <img src="https://pngimg.com/uploads/under_construction/under_construction_PNG29.png" alt="Dashboard Logo" class="w-1/3 md:w-1/4">
            </div>
        </div>
    </div>
</div>
@endsection