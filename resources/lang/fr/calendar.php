<?php

return [
    // Messages
    'messages' => [
        'week_type_updated' => 'Type de semaine mis à jour avec succès',
        'workout_moved' => ':type déplacé au :date',
        'workout_copied' => ':type copié au :date',
        'error_moving_workout' => 'Erreur lors du déplacement de l\'entraînement : :error',
        'error_copying_workout' => 'Erreur lors de la copie de l\'entraînement : :error',
        'auth_required' => 'Authentification utilisateur requise',
        'workouts_deleted' => ':count séances d\'entraînement supprimées avec succès',
        'generic_error' => 'Erreur : :message'
    ],
    
    // Date formats
    'date_formats' => [
        'day_month' => 'j M',          // Pour l'affichage début/fin de semaine: "15 jan." (j sans zéro initial)
        'full_date' => 'j F',          // Pour les dates des événements/entraînements: "15 janvier" (j sans zéro initial)
    ],
    
    // Navigation 
    'navigation' => 'Navigation',
    'scroll_to_top' => 'Remonter en haut',
    'current_month' => 'Mois courant',
    'weeks' => 'semaines',

    // Loading states
    'loading' => 'Chargement',
    'preparing_calendar' => 'Préparation de votre calendrier...',
    'year_changing' => 'Changement d\'année en cours...',
    
    // Strava sync
    'strava_synchronization' => 'Synchronisation Strava',
    'retrieving_workout_data' => 'Récupération de vos données d\'entraînement depuis Strava...',
    
    // Global actions
    'collapse_all_weeks' => 'Réduire',
    'expand_all_weeks' => 'Développer',
    'delete_all_workouts' => 'Supprimer entraînements',
    'delete_monthly_workouts' => 'Supprimer entraînements',
    'delete_weekly_workouts' => 'Supprimer entraînements',
    
    // Delete modal
    'delete_modal' => [
        'confirm_deletion' => 'Confirmer la suppression',
        'confirm_monthly_deletion' => 'Confirmer la suppression mensuelle',
        'confirm_weekly_deletion' => 'Confirmer la suppression hebdomadaire',
        'confirm_delete_all' => 'Êtes-vous sûr de vouloir supprimer toutes les séances d\'entraînement pour l\'année :year ?<br>Cela supprimera :count séances d\'entraînement et ne pourra pas être annulé.',
        'confirm_delete_month' => 'Êtes-vous sûr de vouloir supprimer toutes les séances d\'entraînement pour :month :year ?<br>Cela supprimera :count séances d\'entraînement et ne pourra pas être annulé.',
        'confirm_delete_week' => 'Êtes-vous sûr de vouloir supprimer toutes les séances d\'entraînement pour la Semaine :week ?<br>Cela supprimera :count séances d\'entraînement et ne pourra pas être annulé.',
        'delete_all' => 'Tout supprimer',
        'delete_sessions' => 'Supprimer les séances',
        'cancel' => 'Annuler',
    ],
    
    // Stats
    'stats' => [
        'distance' => 'Distance',
        'duration' => 'Durée',
        'elevation' => 'Dénivelé',
    ],
    
    // Calendar elements
    'week' => 'Semaine',
    'set_week_type' => 'Type de semaine',
    'none' => 'Aucun',
    'toggle_week' => 'Basculer la semaine',
    
    // Month names are usually provided by Carbon, but if needed:
    'months' => [
        'january' => 'Janvier',
        'february' => 'Février',
        'march' => 'Mars',
        'april' => 'Avril',
        'may' => 'Mai',
        'june' => 'Juin',
        'july' => 'Juillet',
        'august' => 'Août',
        'september' => 'Septembre',
        'october' => 'Octobre',
        'november' => 'Novembre',
        'december' => 'Décembre',
    ],
];