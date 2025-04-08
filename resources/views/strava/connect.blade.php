<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connect to Strava</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br {{ themeClass('background') }} min-h-screen flex items-center justify-center">
    <div class="max-w-2xl mx-auto px-4 py-16 sm:py-24 lg:py-32">
        <div class="{{ themeClass('card') }} border backdrop-blur-lg rounded-2xl shadow-xl p-8 sm:p-12 lg:p-16 text-center">
            
            <h2 class="text-2xl font-bold {{ themeClass('text-1') }} mb-5">Connect Your Account</h2>
            
            <p class="{{ themeClass('text-2') }} mb-8 text-lg leading-relaxed">
                To access all features of your training calendar, please connect your Strava account.
            </p>

            <!-- Connect button - conservé comme demandé -->
            <a href="{{ route('strava.redirect') }}" class="inline-block transform transition-all duration-300 hover:scale-105 hover:shadow-lg">    
                <img src="{{ asset('storage/images/connect.png') }}" 
                     alt="Connect with Strava"
                     class="w-full max-w-sm mx-auto">
            </a>

            <!-- Benefits -->
            <div class="{{ themeClass('bg-accent') }} bg-opacity-10 rounded-xl p-6 my-8 text-left space-y-4">
                <div class="flex items-center">
                    <i class="fas fa-check {{ themeClass('text-accent') }} mr-3"></i>
                    <span class="{{ themeClass('text-1') }}">Automatic sync of your activities</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-lock {{ themeClass('text-accent') }} mr-3"></i>
                    <span class="{{ themeClass('text-1') }}">Secure access via Strava API</span>
                </div>
            </div>

            <!-- Legal notices -->
            <p class="mt-8 text-sm {{ themeClass('text-3') }}">
                By clicking, you agree to our 
                <a href="#" class="{{ themeClass('text-link') }} hover:underline">Terms of Service</a> 
                and 
                <a href="#" class="{{ themeClass('text-link') }} hover:underline">Privacy Policy</a>.</br> 
                Strava is a registered trademark of Strava, Inc.
            </p>
        </div>
    </div>
</body>
</html>