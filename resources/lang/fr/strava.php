<?php

return [
    // Auth messages
    'auth' => [
        'connected_success' => 'Strava connecté avec succès',
        'connection_error' => 'Erreur de connexion à Strava : :error',
        'no_user_found' => 'Aucun utilisateur authentifié trouvé.',
        'not_user_instance' => 'L\'objet user n\'est pas une instance de User.',
    ],
    
    // Connection messages
    'messages' => [
        'connected' => 'Strava connecté avec succès',
        'connected_sync_started' => 'Strava connecté avec succès. Synchronisation démarrée en arrière-plan.',
        'connection_error' => 'Erreur de connexion à Strava : :error',
        'disconnected' => 'La connexion Strava a été réinitialisée avec succès',
    ],
    
    // Sync messages
    'sync' => [
        'reconnect_required' => 'Impossible de synchroniser. Veuillez vous reconnecter à Strava',
        'one_activity_imported' => '1 nouvelle activité importée',
        'multiple_activities_imported' => ':count nouvelles activités importées',
        'no_new_activities' => 'Aucune nouvelle activité à importer',
        'failed' => 'Échec de la synchronisation',
        'failed_with_error' => 'Échec de la synchronisation : :error',
        'failed_multiple_attempts' => 'Échec de la synchronisation après plusieurs tentatives : :error',
    ],
];
