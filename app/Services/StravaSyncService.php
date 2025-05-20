<?php

namespace App\Services;

use App\Models\{User, Activity};
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleClient;

class StravaSyncService
{
    public function __construct(
        protected StravaAuthService $authService
    ) {}

    public function sync(User $user): array
    {
        if (!$user->strava_token || ($user->strava_expires_at < now()->timestamp && !($user->settings['auto_renew_token'] ?? true))) {
            return ['success' => false, 'redirect' => true, 'route' => 'strava.redirect'];
        }

        if (!$token = $this->authService->refreshUserToken($user)?->strava_token) {
            return ['success' => false, 'message' => __('strava.sync.reconnect_required')];
        }

        $activities = $this->fetchNewActivities($user, $token);
        $this->saveActivities($user, $activities);

        return [
            'success' => true,
            'message' => $this->buildResultMessage(count($activities)),
            'count' => count($activities)
        ];
    }

    private function fetchNewActivities(User $user, string $token): array
    {
        $httpClient = new GuzzleClient();
        $page = 1;
        $newActivities = [];
        $existingIds = Activity::where('user_id', $user->id)->pluck('strava_id')->toArray();

        do {
            $fetched = $this->fetchActivitiesPage($httpClient, $token, $page);
            $filtered = $this->filterNewRuns($fetched, $existingIds);
            $newActivities = array_merge($newActivities, $filtered);
            $page++;
        } while (!empty($fetched));

        return $newActivities;
    }

    private function fetchActivitiesPage(GuzzleClient $client, string $token, int $page): array
    {
        $response = $client->get('https://www.strava.com/api/v3/athlete/activities', [
            'headers' => ['Authorization' => 'Bearer ' . $token],
            'query' => ['page' => $page, 'per_page' => 200]
        ]);

        return json_decode($response->getBody()->getContents(), true) ?: [];
    }

    private function filterNewRuns(array $activities, array $existingIds): array
    {
        return array_filter($activities, fn($a) => 
            $a['type'] === 'Run' && !in_array($a['id'], $existingIds)
        );
    }

    private function saveActivities(User $user, array $activities): void
    {
        foreach ($activities as $activity) {
            Activity::updateOrCreate(
                ['strava_id' => $activity['id']],
                $this->mapActivityFields($user, $activity)
            );
        }
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