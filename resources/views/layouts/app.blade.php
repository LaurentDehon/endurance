<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>@yield('title', config('app.name')) - Training Planning Application for Runners</title>
        <meta name="description" content="@yield('meta_description', 'Zone 2 - Create personalized, structured training plans tailored to your running goals. Sync with Strava and analyze your performance.')">
        <meta name="keywords" content="@yield('meta_keywords', 'running training plan, running planning, marathon calendar, run tracking, periodization, trail training, zone 2')">
        <meta name="author" content="Zone 2">
        <meta name="robots" content="index, follow">
        
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="@yield('og_title', config('app.name').' - Training Planning Application for Runners')">
        <meta property="og:description" content="@yield('og_description', 'Zone 2 - Create personalized, structured training plans tailored to your running goals. Sync with Strava and analyze your performance.')">
        <meta property="og:image" content="@yield('og_image', asset('storage/images/zone2-social-share.png'))">
        
        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url()->current() }}">
        <meta property="twitter:title" content="@yield('twitter_title', config('app.name').' - Training Planning Application for Runners')">
        <meta property="twitter:description" content="@yield('twitter_description', 'Zone 2 - Create personalized, structured training plans tailored to your running goals.')">
        <meta property="twitter:image" content="@yield('twitter_image', asset('storage/images/zone2-social-share.png'))">
        
        <!-- Canonical URL -->
        <link rel="canonical" href="{{ url()->current() }}">

        <!-- Styles -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
        
        <!-- Tippy.js -->
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="https://unpkg.com/tippy.js@6"></script>
        <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css" />
        
        <!-- Scripts -->
        @livewireStyles 
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Structured Data for Rich Snippets -->
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "SoftwareApplication",
            "name": "{{ config('app.name') }}",
            "applicationCategory": "SportsApplication",
            "operatingSystem": "Web",
            "offers": {
                "@type": "Offer",
                "price": "0",
                "priceCurrency": "USD"
            },
            "description": "Running training plan application. Create personalized and structured plans."
        }
        </script>
    </head>

    <body class="min-h-screen flex flex-col @guest bg-gradient-fix @endguest">
        @auth
            <div class="flex-grow bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800">
                <nav class="top-0 w-full" x-data="{ isMobileMenuOpen: false }">
                    <div class="mx-auto px-4">
                        <div class="flex justify-between h-16">
                            <!-- Left menu -->
                            <div class="flex items-center">
                                <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="lg:hidden p-2 rounded-md text-white hover:bg-opacity-10 focus:outline-none">
                                    <i class="fas fa-bars text-lg"></i>
                                </button>
                                
                                <!-- Main navigation -->
                                <x-navigation />
                            </div>

                            <!-- Right menu -->
                            <div class="flex items-center gap-4"> 
                                <!-- User menu -->
                                <x-user-menu />

                                <!-- Mobile navigation component -->
                                <x-mobile-navigation />
                            </div>
                        </div>
                    </div>
                </nav>
            
                <!-- Main content -->
                <main class="flex-grow layout-dependent">
                    <div class="h-full">
                        @yield('content')
                    </div>
                </main>
            </div>
        @endauth

        @guest
            <div class="flex-grow bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800">
                <div class="flex-grow">
                    @yield('content')
                </div>
            </div>
        @endguest
        
        <!-- Footer -->
        <footer>
            <x-footer />
        </footer>
        
        <!-- Modals and other UI components -->
        <div id="workout-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
            <div class="modal-content bg-transparent rounded-lg mx-auto mt-5 max-w-2xl relative max-h-[95vh] overflow-y-auto">
                <div id="modal-body"></div>
            </div>
        </div>
        
        <livewire:custom-modal />
        <livewire:confirmation-modal />
        <livewire:toast />
        
        @livewireScripts
        
        @auth
        <script>
            // Détection du fuseau horaire local de l'utilisateur
            document.addEventListener('DOMContentLoaded', function() {
                try {
                    const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                    const currentTimezone = "{{ $user->settings['timezone'] ?? '' }}";
                    
                    // Si le fuseau horaire détecté est différent de celui enregistré, on le met à jour
                    if (timezone && timezone !== currentTimezone) {
                        fetch('{{ route("settings.update-timezone") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ timezone: timezone })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Timezone updated successfully');
                            }
                        })
                        .catch(error => console.error('Error updating timezone:', error));
                    }
                } catch (error) {
                    console.error('Error detecting timezone:', error);
                }
            });
        </script>
        @endauth
    </body>
</html>

<script>
// Toggle body class when mobile menu opens/closes
document.addEventListener('DOMContentLoaded', function() {
    const body = document.body;
    const menuButton = document.querySelector('.lg\\:hidden.p-2.rounded-md');
    const closeButton = document.querySelector('[aria-label="Close menu"]');
    const mobileMenu = document.querySelector('.lg\\:hidden.fixed.inset-0');
    
    if (menuButton && closeButton && mobileMenu) {
        menuButton.addEventListener('click', function() {
            body.classList.add('mobile-menu-open');
        });
        
        closeButton.addEventListener('click', function() {
            body.classList.remove('mobile-menu-open');
        });
        
        // Handle click-away on the overlay (outside the menu)
        mobileMenu.addEventListener('click', function(e) {
            // Only trigger if clicking directly on the backdrop (not on the menu itself)
            if (e.target === mobileMenu) {
                body.classList.remove('mobile-menu-open');
            }
        });
    }
});
// Fonctions améliorées pour le drag & drop
function onDragStart(e, workoutId) {
    const isCopy = e.ctrlKey || e.metaKey;
    
    // Définir les données à transférer
    e.dataTransfer.setData('text/plain', JSON.stringify({
        workoutId,
        isCopy
    }));
    
    // Définir l'effet du drag (copier ou déplacer)
    e.dataTransfer.effectAllowed = isCopy ? 'copy' : 'move';
    
    // Appliquer un retour visuel
    if(isCopy) {
        e.currentTarget.classList.add('dragging-copy');
    } else {
        e.currentTarget.classList.add('opacity-50');
    }
}

function onDragOver(e) {
    // Très important: empêcher le comportement par défaut pour permettre le drop
    e.preventDefault();
    
    // Définir l'effet de drop selon le mode (copie ou déplacement)
    if (e.dataTransfer.getData('text/plain')) {
        try {
            const data = JSON.parse(e.dataTransfer.getData('text/plain'));
            e.dataTransfer.dropEffect = data.isCopy ? 'copy' : 'move';
        } catch (error) {
            e.dataTransfer.dropEffect = 'move';
        }
    }
    
    // Ajouter une classe pour le retour visuel
    e.currentTarget.classList.add('drag-over');
}

function onDragLeave(e) {
    // Supprimer la classe de retour visuel
    e.currentTarget.classList.remove('drag-over');
}

function onDrop(e, newDate) {
    // Empêcher le comportement par défaut
    e.preventDefault();
    e.stopPropagation();
    
    try {
        // Extraire les données
        const data = JSON.parse(e.dataTransfer.getData('text/plain'));
        const workoutId = data.workoutId;
        const isCopy = data.isCopy;
        
        // Supprimer le retour visuel
        e.currentTarget.classList.remove('drag-over');
        
        // Trouver l'élément original pour lui retirer la classe d'opacité
        document.querySelectorAll('.opacity-50, .dragging-copy').forEach(el => {
            el.classList.remove('opacity-50', 'dragging-copy');
        });
        
        // Déclencher l'événement Livewire approprié
        if(isCopy) {
            Livewire.dispatch('workout-copied', {
                workoutId: parseInt(workoutId),
                newDate: newDate
            });
        } else {
            Livewire.dispatch('workout-moved', {
                workoutId: parseInt(workoutId),
                newDate: newDate
            });
        }
    } catch (error) {
        console.error('Error during drop:', error);
    }
}
</script>