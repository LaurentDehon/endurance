<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Day;
use App\Models\{User, Activity};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

class StravaSyncService
{
    public function __construct(
        protected StravaAuthService $authService
    ) {}

    public function sync(User $user): array
    {
        // S'assurer que la locale de l'utilisateur est définie pour les traductions
        $originalLocale = app()->getLocale();
        $userLocale = $user->settings['language'] ?? config('app.locale');
        app()->setLocale($userLocale);

        try {
            // Vérifier si l'utilisateur a un token Strava - s'il est null, rediriger pour la première connexion
            if (!$user->strava_token) {
                return ['success' => false, 'redirect' => true, 'route' => 'strava.redirect'];
            }

            // Renouveler automatiquement le token pour tous les utilisateurs
            $refreshedUser = $this->authService->refreshUserToken($user);
            if (!$refreshedUser || !$refreshedUser->strava_token) {
                // Si le renouvellement échoue, rediriger vers Strava pour une nouvelle autorisation
                return ['success' => false, 'redirect' => true, 'route' => 'strava.redirect'];
            }

            // Utiliser le token actualisé
            $token = $refreshedUser->strava_token;

            // Utiliser une transaction pour s'assurer que toutes les activités sont ajoutées d'un coup
            return DB::transaction(function () use ($refreshedUser, $token, $userLocale) {
                // S'assurer que la locale utilisateur est maintenue dans la transaction
                app()->setLocale($userLocale);
                
                $activities = $this->fetchNewActivities($refreshedUser, $token);
                $this->saveActivities($refreshedUser, $activities);

                return [
                    'success' => true,
                    'message' => $this->buildResultMessage(count($activities)),
                    'count' => count($activities)
                ];
            });
        } finally {
            // Restaurer la locale originale
            app()->setLocale($originalLocale);
        }
    }

    private function fetchNewActivities(User $user, string $token): array
    {
        $httpClient = new GuzzleClient();
        $page = 1;
        $newActivities = [];
        $existingIds = Activity::where('user_id', $user->id)->pluck('strava_id')->toArray();
        $maxPages = 50; // Limite de sécurité pour éviter les boucles infinies
        $consecutiveEmptyPages = 0;

        do {
            try {
                $fetched = $this->fetchActivitiesPage($httpClient, $token, $page);
                
                // Si la page est vide, incrémenter le compteur
                if (empty($fetched)) {
                    $consecutiveEmptyPages++;
                } else {
                    $consecutiveEmptyPages = 0; // Remettre à zéro si on trouve des données
                }
                
                $filtered = $this->filterNewRuns($fetched, $existingIds);
                $newActivities = array_merge($newActivities, $filtered);
                $page++;
                
                // Sortir si on atteint la limite de pages ou trop de pages vides consécutives
                if ($page > $maxPages || $consecutiveEmptyPages >= 2) {
                    break;
                }
                
            } catch (\Exception $e) {
                Log::warning("Erreur lors de la récupération de la page {$page} des activités Strava: " . $e->getMessage());
                break; // Sortir en cas d'erreur HTTP
            }
            
        } while (!empty($fetched));

        return $newActivities;
    }

    private function fetchActivitiesPage(GuzzleClient $client, string $token, int $page): array
    {
        try {
            $response = $client->get('https://www.strava.com/api/v3/athlete/activities', [
                'headers' => ['Authorization' => 'Bearer ' . $token],
                'query' => ['page' => $page, 'per_page' => 200],
                'timeout' => 30 // Timeout de 30 secondes par requête
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return is_array($data) ? $data : [];
            
        } catch (RequestException $e) {
            Log::error("Échec de la requête API Strava pour la page {$page}: " . $e->getMessage());
            return []; // Retourner un tableau vide en cas d'erreur
        }
    }

    private function filterNewRuns(array $activities, array $existingIds): array
    {
        return array_filter($activities, fn($a) => 
            $a['type'] === 'Run' && !in_array($a['id'], $existingIds)
        );
    }

    private function saveActivities(User $user, array $activities): void
    {
        if (empty($activities)) {
            return;
        }

        // Préparer les données pour l'insertion en lot
        $insertData = [];
        $now = Carbon::now();
        
        foreach ($activities as $activity) {
            $activityData = $this->mapActivityFields($user, $activity);
            $activityData['created_at'] = $now;
            $activityData['updated_at'] = $now;
            
            // Assigner le day_id manuellement car upsert ne déclenche pas les événements Eloquent
            // Passer l'ID utilisateur pour gérer le contexte du job de queue où Auth::id() retourne null
            $day = Day::findByDateOrCreate($activityData['start_date'], $user->id);
            $activityData['day_id'] = $day->id;
            
            $insertData[] = $activityData;
        }

        // Utiliser upsert pour insérer ou mettre à jour en lot
        // Cela évite les doublons basés sur strava_id et user_id
        Activity::upsert(
            $insertData,
            ['strava_id', 'user_id'], // Colonnes uniques pour détecter les doublons
            [
                'name', 'type', 'start_date', 'distance', 'moving_time', 'elapsed_time',
                'average_speed', 'max_speed', 'average_heartrate', 'max_heartrate',
                'total_elevation_gain', 'elev_high', 'elev_low', 'sync_date',
                'kudos_count', 'description', 'calories', 'map_polyline', 'day_id', 'updated_at'
            ] // Colonnes à mettre à jour si l'enregistrement existe déjà
        );
    }

    private function mapActivityFields(User $user, array $activity): array
    {
        return [
            'name' => $activity['name'],
            'type' => $activity['type'],
            'start_date' => Carbon::parse($activity['start_date_local']),
            'distance' => $activity['distance'],
            'moving_time' => $activity['moving_time'],
            'elapsed_time' => $activity['elapsed_time'],
            'average_speed' => $activity['average_speed'],
            'max_speed' => $activity['max_speed'],
            'average_heartrate' => $activity['average_heartrate'] ?? 0,
            'max_heartrate' => $activity['max_heartrate'] ?? 0,
            'total_elevation_gain' => $activity['total_elevation_gain'],
            'elev_high' => $activity['elev_high'] ?? 0,
            'elev_low' => $activity['elev_low'] ?? 0,
            'user_id' => $user->id,
            'strava_id' => $activity['id'],
            'sync_date' => Carbon::now(),
            'kudos_count' => $activity['kudos_count'],
            'description' => $activity['description'] ?? '',
            'calories' => $activity['calories'] ?? 0,
            'map_polyline' => $activity['map']['summary_polyline'] ?? ''
        ];
    }

    private function buildResultMessage(int $count): string
    {
        if ($count > 0) {
            return $count == 1 
                ? __('strava.sync.one_activity_imported')
                : __('strava.sync.multiple_activities_imported', ['count' => $count]);
        } else {
            return __('strava.sync.no_new_activities');
        }
    }
}