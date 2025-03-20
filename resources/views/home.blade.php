@extends('layouts.app')
@section('content')
    <div class="flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-5xl w-full mx-auto">
            <h2 class="text-2xl text-center font-bold text-gray-800 mb-6">Bienvenue sur Endurance</h2>
            <p class="text-gray-600 mb-4">
                Merci de participer à cette phase de test de notre application Endurance.
                Votre rôle est crucial pour nous aider à améliorer l'expérience utilisateur grâce à vos retours et suggestions.
            </p>
            <p class="text-gray-600 mb-4">
                Endurance a été conçu comme un outil d'aide à la création de plans d'entraînement personnalisés.<br>
                Là où de nombreux coureurs ont recours à des tableaux Excel pour suivre leur programme, 
                notre solution propose une alternative automatisée et interactive avec des fonctionnalités avancées.
                Il faut donc voir Endurance plutôt comme un journal d'entraînement intelligent que comme un créateur de plans d'entraînement.
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
                <strong>Note importante :</strong> L'application est actuellement en version beta anglaise.
                Une localisation française complète est prévue pour la version finale.
                Certaines fonctionnalités sont encore en développement et des bugs résiduels peuvent apparaître.
            </p>
            <div class="mb-4">
                <h3 class="font-semibold text-gray-800 mb-2">Exemple d'utilisation :</h3>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li>Je commence par créer un "training" le 14 juin, représentant mon objectif de course.</li>
                    <li>Pour me préparer, je définis des semaines d'entraînement, remontant dans le temps :</li>
                    <ul class="list-disc pl-6 text-gray-600 space-y-2">
                        <li>La semaine de la course est définie comme une semaine "Race".</li>
                        <li>Les deux semaines précédentes sont des semaines "Taper", où je réduis progressivement la charge d'entraînement.</li>
                        <li>Les quatre semaines précédentes correspondent aux semaines "Maintain", représentant le pic d'entraînement.</li>
                        <li>Les semaines qui précèdent sont des semaines "Development", axées sur le développement général de ma condition physique.</li>
                        <li>Toutes les 3 ou 4 semaines, j'intègre une semaine "Reduced" pour éviter le surentraînement et permettre une récupération efficace.</li>
                        <li>Lors des semaines de développement, je veille à ne pas augmenter la charge d'entraînement de plus de 10% par semaine.</li>
                    </ul>
                    <li>En fonction de mon objectif (marathon, trail, 10 km, etc.), je répartis mes "trainings" chaque semaine afin de respecter mes objectifs hebdomadaires.</li>
                    <li>Personnellement, je préfère fixer mes objectifs hebdomadaires en termes de temps plutôt qu'en distance.</li>
                </ul>
            </div>            
            <p class="text-gray-600 mb-4 mt-10">
                Nous attendons avec intérêt vos impressions sur :<br>
                - L'ergonomie de l'interface<br>
                - L'utilité des fonctionnalités existantes<br>
                - Des suggestions d'améliorations<br>
                - Des suggestions pour le nom (qui n'est qu'un choix temporaire) et le logo
            </p>
            <p class="text-gray-600 font-medium">
                Utilisez le formulaire de contact intégré pour nous faire part de vos observations à tout moment.
            </p>
        </div>
    </div>
@endsection