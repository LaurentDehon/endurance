<?php

return [
    // Auth messages
    'auth' => [
        'connected_success' => 'Strava connected successfully',
        'connection_error' => 'Error connecting to Strava: :error',
        'no_user_found' => 'No authenticated user found.',
        'not_user_instance' => 'The user object is not an instance of User.',
    ],
    
    // Connection messages
    'messages' => [
        'connected' => 'Strava connected successfully',
        'connected_sync_started' => 'Strava connected successfully. Synchronization started in background.',
        'connection_error' => 'Error connecting to Strava: :error',
        'disconnected' => 'Strava connection has been reset successfully',
    ],
    
    // Sync messages
    'sync' => [
        'reconnect_required' => 'Unable to sync. Please reconnect to Strava',
        'one_activity_imported' => '1 new activity imported',
        'multiple_activities_imported' => ':count new activities imported',
        'no_new_activities' => 'No new activities to import',
        'failed' => 'Sync failed',
        'failed_with_error' => 'Sync failed: :error',
        'failed_multiple_attempts' => 'Sync failed after multiple attempts: :error',
    ],
];
