<?php

return [
    // Messages
    'messages' => [
        'week_type_updated' => 'Week type updated successfully',
        'workout_moved' => ':type moved to :date',
        'workout_copied' => ':type copied to :date',
        'error_moving_workout' => 'Error moving workout: :error',
        'error_copying_workout' => 'Error copying workout: :error',
        'auth_required' => 'User authentication required',
        'workouts_deleted' => ':count workout sessions deleted successfully',
        'generic_error' => 'Error: :message'
    ],
    
    // Date formats
    'date_formats' => [
        'day_month' => 'd M',          // For week start/end display: "15 Jan"
        'full_date' => 'jS \of F',     // For event/workout dates: "15th of January"
    ],
    
    // Navigation 
    'navigation' => 'Navigation',
    'scroll_to_top' => 'Scroll to top',
    'current_month' => 'Current month',
    'weeks' => 'weeks',

    // Loading states
    'loading' => 'Loading',
    'preparing_calendar' => 'Preparing your calendar...',
    'year_changing' => 'Changing year...',
    
    // Strava sync
    'strava_synchronization' => 'Strava Synchronization',
    'retrieving_workout_data' => 'Retrieving your workout data from Strava...',
    
    // Global actions
    'collapse_all_weeks' => 'Collapse',
    'expand_all_weeks' => 'Expand',
    'delete_all_workouts' => 'Delete workouts',
    'delete_monthly_workouts' => 'Delete workouts',
    'delete_weekly_workouts' => 'Delete workouts',
    
    // Delete modal
    'delete_modal' => [
        'confirm_deletion' => 'Confirm deletion',
        'confirm_monthly_deletion' => 'Confirm Monthly Deletion',
        'confirm_weekly_deletion' => 'Confirm Weekly Deletion',
        'confirm_delete_all' => 'Are you sure you want to delete all workout sessions for the year :year?<br>This will remove :count workout sessions and cannot be undone.',
        'confirm_delete_month' => 'Are you sure you want to delete all workout sessions for :month :year?<br>This will remove :count workout sessions and cannot be undone.',
        'confirm_delete_week' => 'Are you sure you want to delete all workout sessions for Week :week?<br>This will remove :count workout sessions and cannot be undone.',
        'delete_all' => 'Delete All',
        'delete_sessions' => 'Delete Sessions',
        'cancel' => 'Cancel',
    ],
    
    // Stats
    'stats' => [
        'distance' => 'Distance',
        'duration' => 'Duration',
        'elevation' => 'Elevation',
    ],
    
    // Calendar elements
    'week' => 'Week',
    'set_week_type' => 'Week type',
    'none' => 'None',
    'toggle_week' => 'Toggle week',
    
    // Month names are usually provided by Carbon, but if needed:
    'months' => [
        'january' => 'January',
        'february' => 'February',
        'march' => 'March',
        'april' => 'April',
        'may' => 'May',
        'june' => 'June',
        'july' => 'July',
        'august' => 'August',
        'september' => 'September',
        'october' => 'October',
        'november' => 'November',
        'december' => 'December',
    ],
];