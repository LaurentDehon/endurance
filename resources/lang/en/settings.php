<?php

return [
    'page_title' => 'User Settings',
    'page_description' => 'Customize your application preferences',
    
    'strava' => [
        'title' => 'Strava Connection',
        'connected' => 'Connected to Strava',
        'not_connected' => 'Not connected to Strava',
        'connected_description' => 'Your account is linked to Strava.',
        'not_connected_description' => 'Connect your Strava account to automatically import your running activities.',
        'connection_expires' => 'Connection expires',
        'disconnect' => 'Disconnect',
        'connect' => 'Connect',
        'auto_renew_token' => 'Automatically renew Strava token when expired',
        'sync_on_login' => 'Automatically sync when logging into the site',
        'save_settings' => 'Save Strava Settings',
    ],
    
    'timezone_language' => [
        'title' => 'Timezone & Language Settings',
        'timezone_label' => 'Your Timezone',
        'timezone_placeholder' => 'Select your timezone',
        'timezone_help' => 'Your timezone is automatically detected, but you can change it manually.',
        'language_label' => 'Language',
        'language_help' => 'Choose your preferred language for the application interface.',
        'save_settings' => 'Save Settings',
    ],
    
    'messages' => [
        'updated_successfully' => 'Settings updated successfully.',
        'save_settings' => 'Save',
    ],
];