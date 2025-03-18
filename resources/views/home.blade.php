@extends('layouts.app')
@section('content')
    <div class="flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-3xl w-full mx-auto">
            <h2 class="text-2xl text-center font-bold text-gray-800 mb-6">Bienvenue sur Endurance</h2>
            <p class="text-gray-600 mb-4">
                Merci d'avoir participé à cette phase de test de notre application Endurance.<br>
                Votre rôle est crucial pour nous aider à améliorer l'expérience utilisateur grâce à vos retours et suggestions.
            </p>
            <p class="text-gray-600 mb-4">
                Endurance a été conçue comme un outil d'aide à la création de plans d'entraînement personnalisés.<br>
                Là où de nombreux coureurs ont recours à des tableaux Excel pour suivre leur programme, 
                notre solution propose une alternative automatisée et interactive avec des fonctionnalités avancées.
            </p>
            <div class="mb-4">
                <h3 class="font-semibold text-gray-800 mb-2">Fonctionnalités clés :</h3>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li>Un calendrier annuel interactif pour visualiser et gérer vos semaines d'entraînement</li>
                    <li>Possibilité de définir des objectifs (distance, durée, dénivelé) pour chaque session</li>
                    <li>Synchronisation automatique avec Strava pour importer vos activités</li>
                    <li>Tableaux de bord comparant vos performances réelles aux objectifs fixés</li>
                    <li>Possibilité de définir des types de semaines d'entraînement (réduit, développement, ...)</li>
                </ul>
            </div>
            <p class="text-gray-600 mb-4">
                <strong>Note importante :</strong> L'application est actuellement en version beta anglaise.<br>
                Une localisation française complète est prévue pour la version finale. Certaines fonctionnalités 
                sont encore en développement et des bugs résiduels peuvent apparaître.
            </p>
            <p class="text-gray-600 mb-4">
                Nous attendons avec intérêt vos impressions sur :<br>
                - L'ergonomie de l'interface<br>
                - L'utilité des fonctionnalités existantes<br>
                - Des suggestions d'améliorations<br>
                - Des suggestions pour le nom et le logo
            </p>
            <p class="text-gray-600 font-medium">
                Utilisez le formulaire de contact intégré pour nous faire part de vos observations à tout moment.
            </p>
        </div>
    </div>
@endsection