<?php

return [
    // En-tête
    'faq_title' => 'FAQ - Foire Aux Questions',
    'faq_subtitle' => 'Toutes les réponses à vos questions sur Zone 2',
    'general_questions' => 'Questions Générales',
    'go_to_calendar' => 'Aller au Calendrier',
    
    // Navigation
    'general' => 'Général',
    'planning' => 'Planification',
    'metrics' => 'Métriques',
    'strava' => 'Strava',
    'weeks' => 'Semaines',
    'workouts' => 'Entraînements',
    'tips' => 'Conseils',
    'data' => 'Données',
    
    // Fonctionnalités
    'strava_sync' => 'Synchronisation Strava',
    'weekly_planning' => 'Planification Hebdomadaire',
    'performance_tracking' => 'Suivi de Performance',
    'pro_tips' => 'Conseils Pro',
    
    // Section Générale
    'general_questions_title' => 'Questions Générales',
    'what_is_zone2' => 'Qu\'est-ce que Zone 2 ?',
    'what_is_zone2_answer_1' => 'Zone 2 est un calendrier d\'entraînement interactif qui combine vos activités sportives réelles (synchronisées depuis Strava) avec vos séances d\'entraînement planifiées.',
    'what_is_zone2_answer_2' => 'C\'est un outil conçu pour les athlètes qui souhaitent structurer leur progression et suivre méthodiquement leurs performances.',
    'main_features' => 'Quelles sont les principales fonctionnalités de Zone 2 ?',
    'main_features_list' => [
        'Visualisation des charges d\'entraînement hebdomadaires/mensuelles/annuelles',
        'Comparaison entre les performances planifiées et réelles',
        'Organisation des cycles d\'entraînement avec différents types de semaines',
        'Glisser-déposer des séances d\'entraînement',
        'Suivi de la progression avec des indicateurs visuels',
        'Adaptation de votre programme en fonction de vos objectifs',
        'Synchronisation avec Strava'
    ],
    'is_zone2_free' => 'Zone 2 est-il gratuit ?',
    'is_zone2_free_answer' => 'Zone 2 est actuellement en bêta et gratuit d\'accès.',
    'works_on_mobile' => 'Zone 2 fonctionne-t-il sur mobile ?',
    'works_on_mobile_answer' => 'Oui, Zone 2 est conçu pour être entièrement responsive et fonctionner sur tous les appareils, des téléphones mobiles aux ordinateurs de bureau. Cependant, certaines fonctionnalités peuvent être limitées sur les écrans plus petits pour le moment.',
    
    // Section Planification
    'training_planning_title' => 'Planification des Entraînements',
    'create_training_plan' => 'Comment créer mon plan d\'entraînement ?',
    'create_training_plan_steps' => [
        'Aller au calendrier',
        'Définir votre objectif principal (marathon, trail running, etc.)',
        'Structurer vos semaines d\'entraînement en blocs appropriés',
        'Planifier vos séances hebdomadaires avec des détails sur le type, la distance, la durée et le dénivelé',
        'Synchroniser avec Strava ou saisir manuellement vos activités terminées'
    ],
    'different_workout_types' => 'Puis-je planifier différents types d\'entraînements ?',
    'different_workout_types_answer' => 'Oui, Zone 2 vous permet de planifier divers types d\'entraînements :',
    'different_workout_types_list' => [
        'easy_run' => '<span class="font-medium">Course Facile :</span> Intensité faible pour développer l\'endurance aérobie sans fatigue excessive',
        'recovery_run' => '<span class="font-medium">Course de Récupération :</span> Course très légère après un effort intense pour favoriser la récupération musculaire',
        'intervals' => '<span class="font-medium">Intervalles :</span> Périodes intenses suivies de repos pour améliorer la vitesse et la condition cardiovasculaire',
        'long_run' => '<span class="font-medium">Sortie Longue :</span> Course soutenue à allure modérée pour développer l\'endurance',
        'fartlek' => '<span class="font-medium">Fartlek :</span> Mélange de course rapide et lente avec des variations de vitesse spontanées',
        'tempo' => '<span class="font-medium">Tempo :</span> Effort soutenu et modéré pour améliorer le seuil de lactate',
        'hill_repeats' => '<span class="font-medium">Répétitions en côte :</span> Sprints intenses en montée suivis de jogging de récupération'
    ],
    'modify_session' => 'Comment modifier une séance planifiée ?',
    'modify_session_steps' => [
        'Cliquez sur la séance dans le calendrier',
        'Modifiez les détails dans le formulaire qui s\'ouvre',
        'Sauvegardez vos modifications'
    ],
    'modify_session_drag_drop' => 'Vous pouvez également déplacer une séance par glisser-déposer directement dans le calendrier. Vous pouvez aussi copier une séance en maintenant la touche <strong>Ctrl</strong> (ou <strong>Cmd</strong> sur Mac) tout en la faisant glisser vers une autre date.',
    
    // Section Métriques
    'performance_metrics_title' => 'Métriques de Performance',
    'distance' => 'Distance',
    'distance_desc' => 'Suivi complet de la distance parcourue par rapport à vos objectifs',
    'duration' => 'Durée',
    'duration_desc' => 'Gestion du temps d\'entraînement et comparaison avec les séances planifiées',
    'elevation' => 'Dénivelé',
    'elevation_desc' => 'Analyse précise des montées et du dénivelé positif',
    'interpret_colors' => 'Comment interpréter les couleurs des métriques ?',
    'interpret_colors_answer' => 'Le code couleur pour les métriques est le suivant :',
    'interpret_colors_list' => [
        'blue' => '<span class="font-semibold text-blue-500">Bleu</span> = Distance',
        'red' => '<span class="font-semibold text-red-500">Rouge</span> = Dénivelé',
        'green' => '<span class="font-semibold text-green-500">Vert</span> = Durée'
    ],
    'interpret_colors_format' => 'Format d\'affichage : <span class="font-semibold">Réel / Planifié</span>',
    'see_progress' => 'Comment puis-je voir ma progression dans le temps ? (pas encore implémenté)',
    'see_progress_steps' => [
        'Aller au tableau de bord',
        'Consulter les graphiques montrant la progression de vos métriques (distance, dénivelé, temps)',
        'Utiliser les filtres pour afficher différentes périodes (semaine, mois, année)',
        'Comparer vos performances réelles avec vos objectifs planifiés'
    ],
    
    // Section Strava
    'strava_sync_title' => 'Synchronisation Strava',
    'connect_strava' => 'Comment connecter mon compte Strava ?',
    'connect_strava_steps' => [
        'En cliquant sur le tableau de bord, le calendrier ou la page des activités, vous serez invité à connecter votre compte Strava',
        'Cliquez sur "Se connecter à Strava"',
        'Autorisez Zone 2 à accéder à vos données Strava',
        'Une fois connecté, vous pourrez synchroniser vos activités depuis le calendrier',
        'Vos activités apparaîtront automatiquement les jours où elles ont été réalisées'
    ],
    'strava_not_syncing' => 'Que faire si mes activités Strava ne se synchronisent pas ?',
    'strava_not_syncing_steps' => [
        'Assurez-vous d\'être correctement connecté à Strava',
        'Cliquez sur le bouton "Synchroniser" dans le calendrier',
        'Si le problème persiste, déconnectez et reconnectez votre compte Strava'
    ],
    'use_without_strava' => 'Puis-je utiliser Zone 2 sans Strava ?',
    'use_without_strava_answer' => 'Non, Zone 2 est conçu pour fonctionner avec les données Strava pour un suivi optimal des performances.',
    
    // Section Types de Semaines
    'week_types_title' => 'Types de Semaines',
    'available_week_types' => 'Types de Semaines Disponibles',
    'development' => 'Développement',
    'development_desc' => 'Semaine à charge élevée conçue pour améliorer l\'endurance, la vitesse ou la force',
    'maintain' => 'Maintien',
    'maintain_desc' => 'Semaine équilibrée qui maintient la condition physique sans stress excessif',
    'reduced' => 'Réduite',
    'reduced_desc' => 'Semaine à volume réduit pour prévenir l\'épuisement et favoriser l\'adaptation',
    'recovery' => 'Récupération',
    'recovery_desc' => 'Semaine à faible intensité axée sur le repos et la récupération active',
    'tapering' => 'Affûtage',
    'tapering_desc' => 'Réduction progressive du volume d\'entraînement avant une compétition',
    'race' => 'Course',
    'race_desc' => 'Semaine de compétition et de récupération',
    'choose_week_type' => 'Comment choisir le bon type de semaine ?',
    'choose_week_type_answer' => 'Le choix du type de semaine dépend de votre plan d\'entraînement global et de la phase dans laquelle vous vous trouvez :',
    'choose_week_type_list' => [
        'Utilisez des semaines de <strong>Développement</strong> pendant les périodes d\'entraînement intensif',
        'Alternez avec des semaines <strong>Réduites</strong> toutes les 3-4 semaines pour favoriser la récupération',
        'Utilisez des semaines de <strong>Maintien</strong> entre les blocs d\'entraînement intensifs',
        'Planifiez une semaine de <strong>Récupération</strong> après une compétition ou un bloc d\'entraînement intense',
        'Incluez 1-3 semaines d\'<strong>Affûtage</strong> avant une compétition importante',
        'Marquez vos compétitions comme des semaines de <strong>Course</strong>'
    ],
    'change_week_type' => 'Comment changer un type de semaine ?',
    'change_week_type_steps' => [
        'Aller à la vue calendrier',
        'Cliquer sur l\'en-tête de la semaine',
        'Sélectionner le type de semaine souhaité dans le menu déroulant'
    ],
    
    // Section Types d'Entraînements
    'workout_types_title' => 'Types d\'Entraînements',
    'available_workout_types' => 'Types d\'Entraînements Disponibles',
    'easy_run' => 'Course Facile (E)',
    'easy_run_desc' => 'Course à faible intensité pour développer l\'endurance aérobie sans fatigue excessive',
    'long_run' => 'Sortie Longue (L)',
    'long_run_desc' => 'Course soutenue à allure modérée pour développer l\'endurance sur de plus longues distances',
    'recovery_run' => 'Course de Récupération (R)',
    'recovery_run_desc' => 'Course très légère après un effort intense pour favoriser la récupération musculaire',
    'fartlek' => 'Fartlek (F)',
    'fartlek_desc' => 'Mélange de course rapide et lente avec des variations de vitesse spontanées',
    'tempo_run' => 'Course Tempo (T)',
    'tempo_run_desc' => 'Effort soutenu, modéré à élevé pour améliorer le seuil de lactate',
    'hill_repeats' => 'Répétitions en Côte (H)',
    'hill_repeats_desc' => 'Sprints intenses en montée suivis de jogging de récupération pour développer force et puissance',
    'intervals' => 'Intervalles (I)',
    'intervals_desc' => 'Périodes intenses suivies de repos pour améliorer la vitesse et la capacité cardiovasculaire',
    'back_to_back' => 'Back to Back (B)',
    'back_to_back_desc' => 'Deux entraînements exigeants en succession rapprochée pour développer l\'endurance et la force mentale',
    'race' => 'Course (R)',
    'race_desc' => 'Événement de compétition - donnez tout ce que vous avez !',
    'choose_workout_type' => 'Comment choisir le bon type d\'entraînement ?',
    'choose_workout_type_answer' => 'Le choix du type d\'entraînement dépend de vos objectifs d\'entraînement et de la phase actuelle de votre plan :',
    'choose_workout_type_list' => [
        'Utilisez des <strong>Courses Faciles</strong> pour développer votre base aérobie et récupérer entre les séances plus difficiles',
        'Incluez des <strong>Sorties Longues</strong> chaque semaine pour développer l\'endurance, surtout en préparation de courses longues distances',
        'Planifiez des <strong>Courses de Récupération</strong> après des entraînements intensifs ou des courses pour favoriser une récupération active',
        'Ajoutez des <strong>Courses Tempo</strong> pour améliorer votre seuil de lactate et votre allure de course soutenable',
        'Incorporez des séances d\'<strong>Intervalles</strong> et de <strong>Fartlek</strong> pour améliorer votre vitesse et votre VO2 max',
        'Utilisez des <strong>Répétitions en Côte</strong> pour développer force et puissance avec moins d\'impact que le travail de vitesse',
        'Incluez des entraînements <strong>Back to Back</strong> stratégiquement lors de l\'entraînement pour des épreuves d\'ultra-distance',
        'Marquez vos compétitions comme <strong>Course</strong> pour les distinguer des séances d\'entraînement'
    ],
    'create_specific_workout' => 'Comment créer un entraînement avec un type spécifique ?',
    'create_specific_workout_steps' => [
        'Aller à la vue calendrier',
        'Cliquer sur le jour où vous souhaitez ajouter un entraînement',
        'Dans le modal d\'entraînement, sélectionner le type d\'entraînement souhaité dans le menu déroulant',
        'Remplir les détails (distance, durée, dénivelé, notes)',
        'Sauvegarder votre entraînement'
    ],
    'create_specific_workout_note' => 'Vous pouvez également créer des entraînements récurrents du même type pour des blocs d\'entraînement cohérents.',
    'letters_meaning' => 'Que signifient les lettres entre parenthèses ?',
    'letters_meaning_answer' => 'Les lettres entre parenthèses sont des codes courts utilisés pour identifier rapidement le type d\'entraînement dans la vue calendrier et pendant la planification :',
    'letters_meaning_list' => [
        'E' => '<strong>E</strong> = Course Facile',
        'L' => '<strong>L</strong> = Sortie Longue',
        'R' => '<strong>R</strong> = Course de Récupération ou Course (selon le contexte)',
        'F' => '<strong>F</strong> = Fartlek',
        'T' => '<strong>T</strong> = Course Tempo',
        'H' => '<strong>H</strong> = Répétitions en Côte',
        'I' => '<strong>I</strong> = Intervalles',
        'B' => '<strong>B</strong> = Back to Back'
    ],
    
    // Section Conseils
    'expert_tips_title' => 'Conseils d\'Expert',
    'structure_training' => 'Comment structurer mon entraînement efficacement ?',
    'structure_training_answer' => 'Pour une structure d\'entraînement efficace :',
    'structure_training_list' => [
        'Suivez la règle des 10% (n\'augmentez pas votre volume de plus de 10% par semaine)',
        'Suivez un cycle de 3-4 semaines de charge croissante suivi d\'une semaine de récupération',
        'Variez les types d\'entraînement (endurance, vitesse, force)',
        'Incluez au moins un jour de repos complet par semaine',
        'Planifiez vos séances intensives après des jours de récupération',
        'Adaptez votre plan selon votre ressenti et vos performances réelles'
    ],
    'planning_pitfalls' => 'Quels pièges faut-il éviter dans la planification ?',
    'planning_pitfalls_list' => [
        'Évitez d\'augmenter le volume ou l\'intensité trop rapidement',
        'Ne négligez pas les semaines de récupération',
        'Ne faites pas plusieurs séances intenses consécutives',
        'N\'ignorez pas les signes de fatigue excessive ou de blessure',
        'Ne copiez pas le plan d\'un autre athlète sans l\'adapter à votre niveau',
        'Évitez de planifier des séances trop spécifiques trop tôt dans votre préparation',
        'Rappelez-vous que la constance prime sur l\'intensité pour les progrès à long terme'
    ],
    
    // Section Gestion des Données
    'data_management_title' => 'Gestion des Données',
    'delete_data' => 'Comment supprimer des données ?',
    'delete_data_answer' => 'Utilisez les options de suppression avec précaution :',
    'delete_data_list' => [
        'Pour supprimer une séance individuelle, accédez à la vue de la séance et utilisez l\'icône corbeille',
        'Pour supprimer des semaines/mois/années entiers, utilisez le menu correspondant',
        'Pour supprimer des activités, accédez au menu Activités et utilisez l\'option de suppression'
    ],
    'delete_warning' => 'Attention : les données supprimées ne peuvent pas être récupérées',
    'data_secure' => 'Mes données sont-elles sécurisées ?',
    'data_secure_answer' => 'Oui, vos données sont sécurisées :',
    'data_secure_list' => [
        'Toutes les données sont stockées de manière sécurisée et confidentielle',
        'Nous utilisons uniquement vos informations pour fournir et améliorer le service',
        'Vous conservez un contrôle total sur vos données',
        'Vous pouvez demander la suppression de vos données à tout moment'
    ],
    'see_privacy_policy' => 'Pour plus d\'informations, consultez notre <a href="#" class="text-cyan-400 underline">politique de confidentialité</a>.'
];