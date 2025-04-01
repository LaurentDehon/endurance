@extends('layouts.app')
@section('content')
    <div class="flex flex-col items-center justify-center h-full">
        <!-- Logo -->
        {{-- <div class="mb-8">
            <img src="{{ asset('storage/images/logo_notext_noborder.png') }}" alt="Zone 2 Logo" class="h-44 w-auto">
        </div> --}}
        <!-- Button to open the welcome modal -->
        <button id="openWelcomeModal" class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg shadow-lg text-xl">
            Bienvenue sur Zone 2<br>
            <div class="text-xs mt-1">cliquez ici</div>
        </button>
        
        <!-- Welcome Modal (hidden by default) -->
        <div id="welcomeModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg shadow-xl p-8 max-w-5xl w-full mx-auto max-h-[90vh] overflow-y-auto">
                <h2 class="text-2xl text-center font-bold text-gray-800 mb-10">Bienvenue sur Zone 2</h2>
                <p class="text-gray-600 mb-4">
                    Merci de participer à la phase de bêta-test de Zone 2.  
                    Votre rôle est essentiel pour nous aider à améliorer l'expérience utilisateur grâce à vos retours et suggestions.
                </p>
                <p class="text-gray-600 mb-4">
                    Zone 2 est conçu comme un outil permettant de créer des plans d'entraînement personnalisés.<br>
                    Beaucoup de coureurs utilisent des fichiers Excel pour planifier leurs entraînements.  
                    Notre solution propose une alternative automatisée et interactive avec des fonctionnalités avancées.  
                    Il ne s'agit donc pas seulement d'un générateur de plans, mais plutôt d'un journal d'entraînement intelligent.
                </p>
                <p class="text-gray-600 mb-4">
                    L'application repose sur une approche structurée en blocs, où chaque semaine a un rôle précis et s'intègre dans un cycle global.  
                    Cette organisation permet de mieux gérer la charge d'entraînement, d'optimiser la progression et d'éviter le surentraînement.  
                    Chaque bloc est conçu pour vous préparer progressivement à votre objectif, afin d'être prêt(e) le jour de votre course ou événement.
                </p>

                <div class="mb-4">
                    <h3 class="font-semibold text-gray-800 mb-2">Pourquoi une approche par blocs ?</h3>
                    <p class="text-gray-600 mb-4">
                        Un entraînement efficace ne se limite pas à une simple accumulation de séances. Il repose sur une périodisation structurée, où chaque semaine joue un rôle spécifique dans votre progression.  
                        Zone 2 s'inspire de cette méthode et vous permet de :
                    </p>
                    <ul class="list-disc pl-6 text-gray-600 space-y-2">
                        <li>Planifier vos semaines d'entraînement en fonction d'un objectif précis (développement, maintien, récupération…).</li>
                        <li>Structurer votre charge d'entraînement sur plusieurs semaines pour optimiser la progression et éviter le surentraînement.</li>
                        <li>Adapter chaque séance à son rôle dans le cycle global, plutôt que de la voir comme un événement isolé.</li>
                    </ul>
                </div>

                <div class="mb-4">
                    <h3 class="font-semibold text-gray-800 mb-2">Principales fonctionnalités :</h3>
                    <ul class="list-disc pl-6 text-gray-600 space-y-2">
                        <li>Calendrier annuel interactif pour visualiser et organiser vos semaines d'entraînement.</li>
                        <li>Définition des objectifs (distance, durée, dénivelé) pour chaque séance.</li>
                        <li>Synchronisation automatique avec Strava pour importer vos activités.</li>
                        <li>Tableaux de bord comparant vos performances réelles avec vos objectifs.</li>
                        <li>Possibilité de définir des types de semaines d'entraînement (récupération, développement, maintien, etc.).</li>
                    </ul>
                </div>
                <p class="text-gray-600 mb-4">
                    <strong>Note importante :</strong> L’application est actuellement en version bêta en anglais.  
                    Une traduction complète en français est prévue pour la version finale.  
                    Certaines fonctionnalités sont encore en développement et des bugs peuvent subsister.
                </p>
                <div class="mb-4">
                    <h3 class="font-semibold text-gray-800 mb-2">Exemple d’utilisation :</h3>
                    <ul class="list-disc pl-6 text-gray-600 space-y-2">
                        <li>Je commence par créer un "entraînement" le 14 juin, qui représente mon objectif (ma course).</li>
                        <li>Pour m’y préparer, je définis mes semaines d’entraînement en remontant dans le temps :</li>
                        <ul class="list-disc pl-6 text-gray-600 space-y-2">
                            <li>La semaine de la course est marquée comme "Compétition".</li>
                            <li>Les deux semaines précédentes sont des semaines de "Taper", où je réduis progressivement la charge.</li>
                            <li>Les quatre semaines précédentes sont des semaines de "Maintien", correspondant au pic d'entraînement.</li>
                            <li>Les semaines antérieures sont des semaines de "Développement" pour construire ma condition physique.</li>
                            <li>Une semaine sur trois ou quatre est une semaine "Allégée" pour éviter le surentraînement.</li>
                            <li>Pendant les semaines de développement, je veille à ne pas augmenter ma charge d’entraînement de plus de 10% par semaine.</li>
                        </ul>
                        <li>En fonction de mon objectif (marathon, trail, 10 km…), je répartis mes séances chaque semaine pour atteindre mes objectifs hebdomadaires.</li>
                        <li>Personnellement, je préfère fixer mes objectifs hebdomadaires en temps plutôt qu’en distance.</li>
                    </ul>
                </div>            
                <p class="text-gray-600 mb-4 mt-10">
                    Nous attendons vos retours sur :<br>
                    - L’ergonomie de l’interface<br>
                    - L’utilité des fonctionnalités existantes<br>
                    - Toute suggestion d’amélioration
                </p>
                <p class="text-gray-600 mb-2 font-medium">
                    Utilisez le formulaire de contact intégré pour nous faire part de vos remarques à tout moment.
                </p>
                <p class="text-gray-600">
                    Merci pour votre participation et votre confiance !<br>
                    Laurent
                </p>
                <div class="text-center mt-8">
                    <button id="closeWelcomeModal" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg">
                        J'ai compris
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('welcomeModal');
            const openButton = document.getElementById('openWelcomeModal');
            const closeButton = document.getElementById('closeWelcomeModal');
            
            // Open modal function
            function openModal() {
                modal.classList.remove('hidden');
            }
            
            // Close modal function
            function closeModal() {
                modal.classList.add('hidden');
            }
            
            // Add event listeners
            openButton.addEventListener('click', openModal);
            closeButton.addEventListener('click', closeModal);
            
            // Close modal when clicking outside the content
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeModal();
                }
            });
        });
    </script>
@endsection
