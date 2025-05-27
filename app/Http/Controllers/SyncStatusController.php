<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SyncStatusController extends Controller
{
    /**
     * Vérifie le statut de la synchronisation Strava en arrière-plan
     * Cette route peut être appelée depuis n'importe quelle page
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkStatus()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        // Vérifier si un résultat de synchronisation est disponible
        $cacheKey = "strava_sync_result_{$user->id}";
        $result = cache()->get($cacheKey);
        
        if ($result) {
            // Supprimer le résultat du cache pour éviter les doublons
            cache()->forget("strava_sync_result_{$user->id}");
            cache()->forget("strava_sync_in_progress_{$user->id}");
            
            return response()->json([
                'sync_completed' => true,
                'result' => $result,
                'needs_refresh' => [
                    'calendar' => true,
                    'activities' => true,
                    'dashboard' => true
                ]
            ]);
        }
        
        // Vérifier si une synchronisation est en cours
        $syncInProgress = cache()->has("strava_sync_in_progress_{$user->id}");
        
        return response()->json([
            'sync_completed' => false,
            'sync_in_progress' => $syncInProgress
        ]);
    }
}
