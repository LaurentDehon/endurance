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
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-KSTCWSRP');</script>
        <!-- End Google Tag Manager -->
    </head>

    <body class="min-h-screen flex flex-col @guest bg-gradient-fix @endguest">
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KSTCWSRP"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->

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
        
        <livewire:modal.custom-modal />
        <livewire:modal.confirmation-modal />
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
                                // Timezone updated silently
                            }
                        })
                        .catch(error => {
                            // Silent error handling for timezone update
                        });
                    }
                } catch (error) {
                    // Silent error handling for timezone detection
                }
            });

            // Système de polling global pour la synchronisation Strava
            document.addEventListener('DOMContentLoaded', function() {
                let globalSyncPolling = null;
                let lastSyncInProgress = false;

                // Check for login sync started flag and show toast
                @if(session('login_sync_started'))
                    if (typeof Livewire !== 'undefined') {
                        Livewire.dispatch('toast', ['{{ __('calendar.messages.login_sync_started') }}', 'info']);
                    }
                @endif

                // Traductions pour les messages
                const translations = {
                    syncCompleted: '{{ __('calendar.messages.sync_completed', ['count' => 'COUNT_PLACEHOLDER']) }}',
                    syncNoActivities: '{{ __('calendar.messages.sync_no_activities') }}',
                };

                function startGlobalSyncPolling() {
                    if (globalSyncPolling) {
                        clearInterval(globalSyncPolling);
                    }

                    globalSyncPolling = setInterval(function() {
                        fetch('{{ route("sync.status") }}', {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Si une sync était en cours et qu'elle est maintenant terminée
                            if (lastSyncInProgress && !data.sync_in_progress && data.sync_completed) {
                                // Dispatch du toast via Livewire
                                if (typeof Livewire !== 'undefined') {
                                    if (data.result.success) {
                                        if (data.result.count && data.result.count > 0) {
                                            const message = translations.syncCompleted.replace('COUNT_PLACEHOLDER', data.result.count);
                                            Livewire.dispatch('toast', [message, 'success']);
                                        } else {
                                            Livewire.dispatch('toast', [translations.syncNoActivities, 'info']);
                                        }
                                    } else {
                                        Livewire.dispatch('toast', [data.result.message, 'error']);
                                    }
                                    
                                    // Si un rafraîchissement est nécessaire
                                    if (data.needs_refresh) {
                                        // Marquer les composants qui ont besoin d'être rafraîchis
                                        if (data.needs_refresh.calendar) {
                                            localStorage.setItem('calendar_needs_refresh', 'true');
                                        }
                                        if (data.needs_refresh.activities) {
                                            localStorage.setItem('activities_needs_refresh', 'true');
                                        }
                                        if (data.needs_refresh.dashboard) {
                                            localStorage.setItem('dashboard_needs_refresh', 'true');
                                        }
                                        
                                        // Rafraîchir immédiatement les composants si on est sur leur page
                                        if (window.location.pathname.includes('/calendar') && data.needs_refresh.calendar) {
                                            Livewire.dispatch('sync-completed-refresh');
                                            localStorage.removeItem('calendar_needs_refresh');
                                        }
                                        
                                        if (window.location.pathname.includes('/activities') && data.needs_refresh.activities) {
                                            Livewire.dispatch('activities-sync-refresh');
                                            localStorage.removeItem('activities_needs_refresh');
                                        }
                                        
                                        if ((window.location.pathname === '/' || window.location.pathname.includes('/dashboard')) && data.needs_refresh.dashboard) {
                                            Livewire.dispatch('dashboard-sync-refresh');
                                            localStorage.removeItem('dashboard_needs_refresh');
                                        }
                                    }
                                }
                            }
                            
                            lastSyncInProgress = data.sync_in_progress;
                        })
                        .catch(error => {
                            // Silent error handling for polling
                        });
                    }, 5000); // Vérifier toutes les 5 secondes
                }

                // Démarrer le polling global
                startGlobalSyncPolling();

                // Arrêter le polling quand l'utilisateur quitte la page
                window.addEventListener('beforeunload', function() {
                    if (globalSyncPolling) {
                        clearInterval(globalSyncPolling);
                    }
                });
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

// Ces fonctions sont maintenant gérées uniquement dans calendar.blade.php
// pour éviter les doublons lors du drag and drop
</script>