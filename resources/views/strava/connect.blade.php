<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion à Strava</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="max-w-2xl mx-auto px-4 py-16 sm:py-24 lg:py-32">
        <div class="bg-white rounded-2xl shadow-xl p-8 sm:p-12 lg:p-16 text-center">
            
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                Synchronisation avec Strava
            </h1>
            
            <!-- Logo Strava officiel -->
            <div class="mb-8 flex justify-center">
                <img src="{{ asset('storage/images/strava-logo2.png') }}" alt="Logo Strava" class="h-24 w-24">
            </div>
            
            <p class="text-gray-600 mb-8 text-lg leading-relaxed">
                Pour accéder à toutes les fonctionnalités de votre calendrier d'entraînement, veuillez connecter votre compte Strava.
            </p>

            <!-- Avantages -->
            <div class="bg-orange-50 rounded-lg p-6 mb-8 text-left space-y-4">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Synchronisation automatique de vos activités</span>
                </div>
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <span>Accès sécurisé via l'API Strava</span>
                </div>
            </div>

            <!-- Bouton de connexion -->
            <a href="{{ route('strava.redirect') }}" 
               class="inline-flex items-center justify-center px-8 py-4 border border-transparent 
                      text-lg font-medium rounded-md text-white bg-[#FC4C02]
                      transition-all duration-200 transform hover:scale-105 shadow-lg">
                      <img src="{{ asset('storage/images/strava-logo.svg') }}" alt="Logo Strava" class="h-16 w-16">
                <span class="font-bold">Connecter avec Strava</span>
            </a>

            <!-- Mentions légales -->
            <p class="mt-8 text-sm text-gray-500">
                En cliquant, vous acceptez nos 
                <a href="#" class="text-orange-600 hover:text-orange-700 underline">Conditions d'utilisation</a> 
                et notre 
                <a href="#" class="text-orange-600 hover:text-orange-700 underline">Politique de confidentialité</a>. 
                Strava est une marque déposée de Strava, Inc.
            </p>
        </div>
    </div>
</body>
</html>