<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client as GuzzleClient;

class StravaController extends Controller
{
    public function redirect()
    {
        $clientId = env('STRAVA_CLIENT_ID');
        $redirectUri = env('STRAVA_REDIRECT_URI');
        
        $authUrl = "https://www.strava.com/oauth/authorize?client_id={$clientId}&response_type=code&redirect_uri={$redirectUri}&scope=read,activity:read_all&approval_prompt=force";
        
        return redirect($authUrl);
    }

    public function handleCallback(Request $request)
    {
        $code = $request->input('code');
        $clientId = env('STRAVA_CLIENT_ID');
        $clientSecret = env('STRAVA_CLIENT_SECRET');

        try {
            $httpClient = new GuzzleClient();
            $response = $httpClient->post('https://www.strava.com/oauth/token', [
                'form_params' => [
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'code' => $code,
                    'grant_type' => 'authorization_code',
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            $user = Auth::user();
            /** @var \App\Models\User $user */
            $user->update([
                'strava_token' => $data['access_token'],
                'strava_refresh_token' => $data['refresh_token'],
                'strava_expires_at' => $data['expires_at'],
            ]);

            return redirect()->route('calendar.index')->with('success', 'Successfully connected to Strava');;

        } catch (\Exception $e) {
            return redirect()->route('calendar.index')->with('error', 'Unable to connect to Strava');
        }
    }

    private function refreshToken(User $user)
    {
        $refreshToken = $user->strava_refresh_token;
        $clientId = env('STRAVA_CLIENT_ID');
        $clientSecret = env('STRAVA_CLIENT_SECRET');

        if (!$refreshToken) {
            return null;
        }

        $httpClient = new GuzzleClient();
        $response = $httpClient->post('https://www.strava.com/oauth/token', [
            'form_params' => [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        if (isset($data['access_token'])) {
            $user->update([
                'strava_token' => $data['access_token'],
                'strava_refresh_token' => $data['refresh_token'],
                'strava_expires_at' => $data['expires_at'],
            ]);

            return $data['access_token'];
        }

        return null;
    }

    public function showConnect()
    {
        return view('strava.connect');
    }

    public function sync()
    {        
        $user = Auth::user();
        $token = $user->strava_token;
        $expiresAt = $user->strava_expires_at;

        if (!$token || Carbon::now()->timestamp >= $expiresAt) {
            $newToken = $this->refreshToken($user);
    
            if (!$newToken) {                
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to sync with Strava. Please reconnect first'
                ]);
            }
    
            $token = $newToken;
        }

        $httpClient = new GuzzleClient();
        $page = 1;
        $activities = [];
        $existingActivities = Activity::where('user_id', $user->id)->pluck('strava_id')->toArray();

        while (true) {
            $response = $httpClient->get('https://www.strava.com/api/v3/athlete/activities', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ],
                'query' => [
                    'page' => $page,
                    'per_page' => 200
                ]
            ]);

            $fetchedActivities = json_decode($response->getBody()->getContents(), true);

            if (empty($fetchedActivities)) {
                break;
            }

            foreach ($fetchedActivities as $activity) {
                if ($activity['type'] === 'Run' && !in_array($activity['id'], $existingActivities)) {
                    $activities[] = $activity;
                }
            }

            $page++;
        }

        foreach ($activities as $activity) {
            Activity::updateOrCreate(
                ['strava_id' => $activity['id']],
                [
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
                ]
            );
        }

        if (count($activities) > 0) {
            $message = 'Synchronization successful. ' . count($activities) . ' activities imported';
        }
        else {
            $message = 'Synchronization successful. No new activities found';
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}