<?php

return [
    // Header
    'faq_title' => 'FAQ - Frequently Asked Questions',
    'faq_subtitle' => 'All the answers to your questions about Zone 2',
    'general_questions' => 'General Questions',
    'go_to_calendar' => 'Go to Calendar',
    
    // Navigation
    'general' => 'General',
    'planning' => 'Planning',
    'metrics' => 'Metrics',
    'strava' => 'Strava',
    'weeks' => 'Weeks',
    'workouts' => 'Workouts',
    'tips' => 'Tips',
    'data' => 'Data',
    
    // Features
    'strava_sync' => 'Strava Sync',
    'weekly_planning' => 'Weekly Planning',
    'performance_tracking' => 'Performance Tracking',
    'pro_tips' => 'Pro Tips',
    
    // General Section
    'general_questions_title' => 'General Questions',
    'what_is_zone2' => 'What is Zone 2?',
    'what_is_zone2_answer_1' => 'Zone 2 is an interactive training calendar that combines your actual sports activities (synced from Strava) with your planned workout sessions.',
    'what_is_zone2_answer_2' => 'It\'s a tool designed for athletes who want to structure their progression and methodically track their performance.',
    'main_features' => 'What are the main features of Zone 2?',
    'main_features_list' => [
        'Visualization of weekly/monthly/yearly training loads',
        'Comparison between planned and actual performances',
        'Organization of training cycles with different week types',
        'Drag and drop workout sessions',
        'Track progress with visual indicators',
        'Adapt your schedule based on your goals',
        'Strava synchronization'
    ],
    'is_zone2_free' => 'Is Zone 2 free?',
    'is_zone2_free_answer' => 'Zone 2 is currently in beta and free to access.',
    'works_on_mobile' => 'Does Zone 2 work on mobile?',
    'works_on_mobile_answer' => 'Yes, Zone 2 is designed to be fully responsive and work on all devices, from mobile phones to desktop computers. However some functionalities may be limited on smaller screens at the moment.',
    
    // Planning Section
    'training_planning_title' => 'Training Planning',
    'create_training_plan' => 'How do I create my training plan?',
    'create_training_plan_steps' => [
        'Go to the calendar',
        'Set your main goal (marathon, trail running, etc.)',
        'Structure your training weeks in appropriate blocks',
        'Plan your weekly sessions with details on type, distance, duration, and elevation',
        'Sync with Strava or manually enter your completed activities'
    ],
    'different_workout_types' => 'Can I plan different types of workouts?',
    'different_workout_types_answer' => 'Yes, Zone 2 allows you to plan various types of workouts:',
    'different_workout_types_list' => [
        'easy_run' => '<span class="font-medium">Easy Run:</span> Low intensity to develop aerobic endurance without excessive fatigue',
        'recovery_run' => '<span class="font-medium">Recovery Run:</span> Very light run after intense effort to promote muscle recovery',
        'intervals' => '<span class="font-medium">Intervals:</span> Intense periods followed by rest to improve speed and cardiovascular condition',
        'long_run' => '<span class="font-medium">Long Run:</span> Sustained run at moderate pace to develop endurance',
        'fartlek' => '<span class="font-medium">Fartlek:</span> Mix of fast and slow running with spontaneous speed variations',
        'tempo' => '<span class="font-medium">Tempo:</span> Sustained, moderate effort to improve lactate threshold',
        'hill_repeats' => '<span class="font-medium">Hill Repeats:</span> Intense uphill sprints followed by recovery jogs'
    ],
    'modify_session' => 'How do I modify a planned session?',
    'modify_session_steps' => [
        'Click on the session in the calendar',
        'Edit the details in the form that opens',
        'Save your changes'
    ],
    'modify_session_drag_drop' => 'You can also move a session by drag-and-drop directly in the calendar. You can also copy a session by holding the <strong>Ctrl</strong> key (or <strong>Cmd</strong> on Mac) while dragging it to another date.',
    
    // Metrics Section
    'performance_metrics_title' => 'Performance Metrics',
    'distance' => 'Distance',
    'distance_desc' => 'Complete tracking of distance covered compared to your goals',
    'duration' => 'Duration',
    'duration_desc' => 'Workout time management and comparison with planned sessions',
    'elevation' => 'Elevation',
    'elevation_desc' => 'Precise analysis of climbs and positive elevation gain',
    'interpret_colors' => 'How do I interpret the metric colors?',
    'interpret_colors_answer' => 'The color code for metrics is as follows:',
    'interpret_colors_list' => [
        'blue' => '<span class="font-semibold text-blue-500">Blue</span> = Distance',
        'red' => '<span class="font-semibold text-red-500">Red</span> = Elevation',
        'green' => '<span class="font-semibold text-green-500">Green</span> = Duration'
    ],
    'interpret_colors_format' => 'Display format: <span class="font-semibold">Actual / Planned</span>',
    'see_progress' => 'How can I see my progress over time? (not yet implemented)',
    'see_progress_steps' => [
        'Go to the dashboard',
        'Check the charts showing the progression of your metrics (distance, elevation, time)',
        'Use filters to display different periods (week, month, year)',
        'Compare your actual performances with your planned goals'
    ],
    
    // Strava Section
    'strava_sync_title' => 'Strava Synchronization',
    'connect_strava' => 'How do I connect my Strava account?',
    'connect_strava_steps' => [
        'By clicking on the dashboard, calendar or activities page, you will be prompted to connect your Strava account',
        'Click on "Connect to Strava"',
        'Authorize Zone 2 to access your Strava data',
        'Once connected, you will be able to synchronize your activities from the calendar',
        'Your activities will automatically appear on the days they were performed'
    ],
    'strava_not_syncing' => 'What should I do if my Strava activities aren\'t syncing?',
    'strava_not_syncing_steps' => [
        'Make sure you\'re properly connected to Strava',
        'Click the "Sync" button in the calendar',
        'If the problem persists, disconnect and reconnect your Strava account'
    ],
    'use_without_strava' => 'Can I use Zone 2 without Strava?',
    'use_without_strava_answer' => 'No, Zone 2 is designed to work with Strava data for optimal performance tracking.',
    
    // Week Types Section
    'week_types_title' => 'Week Types',
    'available_week_types' => 'Available Week Types',
    'development' => 'Development',
    'development_desc' => 'High-load week designed to improve endurance, speed, or strength',
    'maintain' => 'Maintain',
    'maintain_desc' => 'Balanced week that maintains fitness without excessive stress',
    'reduced' => 'Reduced',
    'reduced_desc' => 'Week with reduced volume to prevent burnout and promote adaptation',
    'recovery' => 'Recovery',
    'recovery_desc' => 'Low-intensity week focused on rest and active recovery',
    'tapering' => 'Tapering',
    'tapering_desc' => 'Progressive reduction of training volume before a competition',
    'race' => 'Race',
    'race_desc' => 'Competition and recovery week',
    'choose_week_type' => 'How to choose the right week type?',
    'choose_week_type_answer' => 'The choice of week type depends on your overall training plan and the phase you\'re in:',
    'choose_week_type_list' => [
        'Use <strong>Development</strong> weeks during intensive training periods',
        'Alternate with <strong>Reduced</strong> weeks every 3-4 weeks to promote recovery',
        'Use <strong>Maintain</strong> weeks between intensive training blocks',
        'Schedule a <strong>Recovery</strong> week after a competition or an intense training block',
        'Include 1-3 <strong>Tapering</strong> weeks before an important competition',
        'Mark your competitions as <strong>Race</strong> weeks'
    ],
    'change_week_type' => 'How to change a week type?',
    'change_week_type_steps' => [
        'Go to the calendar view',
        'Click on the week header',
        'Select the desired week type from the dropdown menu'
    ],
    
    // Workout Types Section
    'workout_types_title' => 'Workout Types',
    'available_workout_types' => 'Available Workout Types',
    'easy_run' => 'Easy Run (E)',
    'easy_run_desc' => 'Low intensity run to develop aerobic endurance without excessive fatigue',
    'long_run' => 'Long Run (L)',
    'long_run_desc' => 'Sustained run at moderate pace to develop endurance over longer distances',
    'recovery_run' => 'Recovery Run (R)',
    'recovery_run_desc' => 'Very light run after intense effort to promote muscle recovery',
    'fartlek' => 'Fartlek (F)',
    'fartlek_desc' => 'Mix of fast and slow running with spontaneous speed variations',
    'tempo_run' => 'Tempo Run (T)',
    'tempo_run_desc' => 'Sustained, moderate-to-high effort to improve lactate threshold',
    'hill_repeats' => 'Hill Repeats (H)',
    'hill_repeats_desc' => 'Intense uphill sprints followed by recovery jogs to build strength and power',
    'intervals' => 'Intervals (I)',
    'intervals_desc' => 'Intense periods followed by rest to improve speed and cardiovascular capacity',
    'back_to_back' => 'Back to Back (B)',
    'back_to_back_desc' => 'Two challenging workouts in close succession to build endurance and mental toughness',
    'race' => 'Race (R)',
    'race_desc' => 'Competition event - give it your all!',
    'choose_workout_type' => 'How to choose the right workout type?',
    'choose_workout_type_answer' => 'The choice of workout type depends on your training goals and the current phase of your plan:',
    'choose_workout_type_list' => [
        'Use <strong>Easy Runs</strong> for building base aerobic fitness and recovery between harder sessions',
        'Include <strong>Long Runs</strong> weekly to build endurance, especially when preparing for longer distance races',
        'Schedule <strong>Recovery Runs</strong> after intense workouts or races to promote active recovery',
        'Add <strong>Tempo Runs</strong> to improve your lactate threshold and sustainable race pace',
        'Incorporate <strong>Intervals</strong> and <strong>Fartlek</strong> sessions to improve speed and VO2 max',
        'Use <strong>Hill Repeats</strong> to build strength and power with lower impact than speed work',
        'Include <strong>Back to Back</strong> workouts strategically when training for ultra distance events',
        'Mark your competitions as <strong>Race</strong> to distinguish them from training sessions'
    ],
    'create_specific_workout' => 'How to create a workout with a specific type?',
    'create_specific_workout_steps' => [
        'Go to the calendar view',
        'Click on the day you want to add a workout',
        'In the workout modal, select the desired workout type from the dropdown menu',
        'Fill in the details (distance, duration, elevation, notes)',
        'Save your workout'
    ],
    'create_specific_workout_note' => 'You can also create recurring workouts of the same type for consistent training blocks.',
    'letters_meaning' => 'What do the letters in parentheses mean?',
    'letters_meaning_answer' => 'The letters in parentheses are short codes used to quickly identify the workout type in the calendar view and during planning:',
    'letters_meaning_list' => [
        'E' => '<strong>E</strong> = Easy Run',
        'L' => '<strong>L</strong> = Long Run',
        'R' => '<strong>R</strong> = Recovery Run or Race (context dependent)',
        'F' => '<strong>F</strong> = Fartlek',
        'T' => '<strong>T</strong> = Tempo Run',
        'H' => '<strong>H</strong> = Hill Repeats',
        'I' => '<strong>I</strong> = Intervals',
        'B' => '<strong>B</strong> = Back to Back'
    ],
    
    // Tips Section
    'expert_tips_title' => 'Expert Tips',
    'structure_training' => 'How to structure my training effectively?',
    'structure_training_answer' => 'For an effective training structure:',
    'structure_training_list' => [
        'Follow the 10% rule (don\'t increase your volume by more than 10% per week)',
        'Follow a cycle of 3-4 weeks of increasing load followed by a recovery week',
        'Vary the types of training (endurance, speed, strength)',
        'Include at least one complete rest day per week',
        'Plan your intensive sessions after recovery days',
        'Adapt your plan according to how you feel and your actual performances'
    ],
    'planning_pitfalls' => 'What pitfalls should be avoided in planning?',
    'planning_pitfalls_list' => [
        'Avoid increasing volume or intensity too quickly',
        'Don\'t neglect recovery weeks',
        'Don\'t do multiple intense sessions consecutively',
        'Don\'t ignore signs of excessive fatigue or injury',
        'Don\'t copy another athlete\'s plan without adapting it to your level',
        'Avoid planning sessions that are too specific too early in your preparation',
        'Remember that consistency takes precedence over intensity for long-term progress'
    ],
    
    // Data Management Section
    'data_management_title' => 'Data Management',
    'delete_data' => 'How to delete data?',
    'delete_data_answer' => 'Use deletion options with caution:',
    'delete_data_list' => [
        'To delete an individual session, go to the session view and use the trash icon',
        'To delete entire weeks/months/years, use the corresponding menu',
        'To delete activities, go to the Activities menu and use the delete option'
    ],
    'delete_warning' => 'Warning: deleted data cannot be recovered',
    'data_secure' => 'Is my data secure?',
    'data_secure_answer' => 'Yes, your data is secure:',
    'data_secure_list' => [
        'All data is stored securely and confidentially',
        'We only use your information to provide and improve the service',
        'You maintain complete control over your data',
        'You can request deletion of your data at any time'
    ],
    'see_privacy_policy' => 'For more information, see our <a href="#" class="text-cyan-400 underline">privacy policy</a>.'
];