<?php

namespace App\Listeners;

use App\Jobs\StravaSyncJob;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use App\Services\StravaAuthService;
use App\Services\StravaSyncService;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

/**
 * Listener qui gère les actions à effectuer lors de la connexion d'un utilisateur
 * - Met à jour les informations de dernière connexion
 * - Déclenche automatiquement une synchronisation Strava si applicable
 */
class HandleUserLogin
{
    public function __construct()
    {
        //
    }

    /**
     * Gère l'événement de connexion utilisateur
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        $user->last_login_at = now();
        
        // Enregistrer l'adresse IP si disponible
        if (request()->ip()) {
            $user->last_ip_address = request()->ip();
        }
        
        if ($user instanceof Model) {
            $user->save();
        }

        // Tous les utilisateurs avec un token Strava (même expiré) déclenchent une synchronisation automatique
        if ($user->strava_token) {
            // Vérifier si la dernière sync remonte à moins de 10 minutes pour éviter les synchronisations fréquentes
            if ($user->last_sync_at && $user->last_sync_at->diffInMinutes(now()) < 10) {
                Log::info("Synchronisation automatique ignorée pour l'utilisateur {$user->id} - dernière sync il y a moins de 10 minutes (dernière sync: {$user->last_sync_at})");
                return;
            }

            // Vérifier si une synchronisation est déjà en cours ou en file d'attente
            if (cache()->has("strava_sync_in_progress_{$user->id}") || cache()->has("strava_sync_processing_{$user->id}")) {
                Log::info("Synchronisation automatique ignorée pour l'utilisateur {$user->id} - synchronisation déjà en cours");
                return;
            }
            
            // Vérifier s'il y a déjà des jobs de synchronisation en attente pour cet utilisateur
            $pendingJobs = DB::table('jobs')
                ->where('queue', 'strava-sync')
                ->where('payload', 'like', '%"id";i:' . $user->id . ';%')
                ->count();
                
            if ($pendingJobs > 0) {
                Log::info("Synchronisation automatique ignorée pour l'utilisateur {$user->id} - {$pendingJobs} jobs déjà en file d'attente");
                return;
            }
            
            // Toujours tenter la synchronisation, le service se chargera de renouveler le token automatiquement
            // Marquer la synchronisation comme en cours pour le système de polling
            cache()->put("strava_sync_in_progress_{$user->id}", true, now()->addMinutes(5));
            
            // Dispatcher le job de synchronisation en arrière-plan
            StravaSyncJob::dispatch($user->id)->onQueue('strava-sync');
            
            // Stocker un flag pour afficher le toast de démarrage de sync au prochain chargement de page
            session()->flash('login_sync_started', true);
            
            Log::info("Synchronisation automatique initiée pour l'utilisateur {$user->id} lors de la connexion");
        }
    }
}
