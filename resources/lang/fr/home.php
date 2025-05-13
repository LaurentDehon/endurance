<?php

return [
    'meta' => [
        'title' => 'Zone 2 - Application de planification d\'entraînement de course à pieds',
        'description' => 'Zone 2 vous aide à créer des plans d\'entraînement personnalisés et structurés pour la course à pied. Planifiez vos semaines, synchronisez avec Strava et optimisez votre progression.',
        'keywords' => 'plan d\'entraînement de course, planification de course, marathon, trail, périodisation, zone 2, progression de course, calendrier de course',
        'og_title' => 'Zone 2 - Prenez le contrôle de votre entraînement de course',
        'og_description' => 'Créez des plans d\'entraînement personnalisés, structurés en blocs et adaptés à vos objectifs. Analysez vos performances et synchronisez avec Strava.',
    ],
    'hero' => [
        'title' => 'Prenez le contrôle de votre entraînement',
        'description' => 'Zone 2 vous aide à créer des plans d\'entraînement personnalisés et structurés adaptés à vos objectifs. Une approche progressive et claire que vous contrôlez.',
        'create_plan' => 'Créer mon plan d\'entraînement',
        'sign_in' => 'Se connecter pour commencer',
        'discover' => 'Découvrir les fonctionnalités',
        'help' => 'Centre d\'aide',
    ],
    'why_section' => [
        'title' => 'Pourquoi Zone 2 ?',
        'description' => 'De nombreux coureurs planifient encore leurs entraînements dans Excel ou sur papier. Zone 2 offre une alternative interactive et structurée, conçue pour ceux qui veulent suivre leur progression méthodiquement.',
        'tagline' => 'Zone 2 n\'est pas un générateur de plans, c\'est votre compagnon d\'entraînement intelligent qui évolue avec vous.',
    ],
    'structure_section' => [
        'title' => 'Une structure conçue pour la progression',
        'subtitle' => 'Pensez en blocs, progressez intelligemment',
        'description_1' => 'Zone 2 est basé sur une périodisation structurée. Chaque semaine joue un rôle spécifique dans votre progression.',
        'description_2' => 'Cette méthode aide à équilibrer la charge d\'entraînement et à optimiser les performances.',
        'week_types' => [
            'recovery' => [
                'title' => 'Récupération',
                'description' => 'Une semaine d\'intensité légère axée sur le repos et la récupération active',
            ],
            'development' => [
                'title' => 'Développement',
                'description' => 'Une semaine à charge élevée conçue pour améliorer l\'endurance, la vitesse ou la force',
            ],
            'maintain' => [
                'title' => 'Maintien',
                'description' => 'Une semaine équilibrée qui maintient la condition physique sans stress excessif',
            ],
            'reduced' => [
                'title' => 'Réduite',
                'description' => 'Une semaine avec un volume réduit pour prévenir l\'épuisement et permettre l\'adaptation',
            ],
            'taper' => [
                'title' => 'Affûtage',
                'description' => 'Une réduction progressive du volume d\'entraînement avant une course',
            ],
            'race' => [
                'title' => 'Course',
                'description' => 'Semaine de compétition comprenant la course et la récupération',
            ],
        ],
    ],
    'features_section' => [
        'title' => 'Fonctionnalités clés',
        'subtitle' => 'Tout ce dont vous avez besoin pour planifier, suivre et ajuster',
        'features' => [
            'calendar' => [
                'title' => 'Calendrier interactif',
                'description' => 'Visualisez votre plan par semaine et par mois avec une interface intuitive',
            ],
            'goals' => [
                'title' => 'Configuration précise des objectifs',
                'description' => 'Définissez vos cibles en durée, distance et dénivelé pour chaque séance',
            ],
            'strava' => [
                'title' => 'Synchronisation Strava',
                'description' => 'Importez automatiquement vos activités pour les comparer à votre plan',
            ],
            'dashboards' => [
                'title' => 'Tableaux de bord',
                'description' => 'Analysez vos performances et comparez les résultats prévus vs réels',
            ],
            'week_types' => [
                'title' => 'Types de semaines personnalisés',
                'description' => 'Adaptez vos semaines selon vos phases d\'entraînement spécifiques',
            ],
            'overload_alert' => [
                'title' => 'Alerte de surcharge',
                'description' => 'Recevez des notifications si vous dépassez la règle d\'augmentation de 10%',
            ],
            'block_structure' => [
                'title' => 'Structure de bloc cohérente',
                'description' => 'Organisez vos semaines en fonction de vos objectifs spécifiques',
            ],
            'custom_plans' => [
                'title' => 'Plans 100% personnalisés',
                'description' => 'Créez et adaptez votre plan selon vos besoins spécifiques',
            ],
            'annual_planning' => [
                'title' => 'Planification annuelle claire',
                'description' => 'Visualisez et organisez votre année d\'entraînement en quelques clics',
            ],
        ],
    ],
    'how_section' => [
        'title' => 'Comment ça marche',
        'subtitle' => 'Votre entraînement en 5 étapes',
        'steps' => [
            'step_1' => [
                'title' => 'Créez votre objectif',
                'description' => 'Définissez votre objectif principal (ex. Marathon, Trail 50K) et la date de l\'événement',
            ],
            'step_2' => [
                'title' => 'Organisez vos semaines',
                'description' => 'Structurez vos semaines en blocs adaptés à votre objectif (développement, maintien, récupération...)',
            ],
            'step_3' => [
                'title' => 'Ajoutez vos séances',
                'description' => 'Planifiez vos séances hebdomadaires avec des détails (type, distance, durée, dénivelé)',
            ],
            'step_4' => [
                'title' => 'Synchronisez avec Strava',
                'description' => 'Connectez votre compte Strava pour importer automatiquement vos activités et les comparer à votre plan',
            ],
            'step_5' => [
                'title' => 'Suivez et ajustez',
                'description' => 'Modifiez votre plan en fonction de votre ressenti et de vos performances réelles',
            ],
        ],
    ],
    'training_blocks' => [
        'title' => 'Exemple de plan d\'entraînement marathon de 16 semaines',
        'tagline' => 'Une progression stratégique du développement initial jusqu\'au jour de la course, avec des périodes de récupération régulières pour optimiser l\'adaptation.',
        'blocks' => [
            'development_1' => [
                'weeks' => 'Semaines 1-3',
                'title' => 'Préparation générale (Développement)',
                'description' => 'Phase de développement axée sur la construction d\'une base aérobie',
                'points' => [
                    'Augmentation progressive du volume semaine après semaine',
                    'Travail de fondation à faible intensité pour développer l\'endurance',
                    'Établissement d\'une routine de course et de constance',
                ],
            ],
            'reduced_1' => [
                'weeks' => 'Semaine 4',
                'title' => 'Première récupération (Réduite)',
                'description' => 'Réduction planifiée du volume pour permettre l\'adaptation',
                'points' => [
                    'Réduction de volume de 20-30% par rapport aux semaines précédentes',
                    'Focus sur les séances de récupération active',
                    'Accent sur le repos supplémentaire et le sommeil',
                ],
            ],
            'development_2' => [
                'weeks' => 'Semaines 5-7',
                'title' => 'Préparation spécifique (Développement)',
                'description' => 'Développement progressif avec intensité croissante',
                'points' => [
                    'Introduction des courses à tempo et de travail de vitesse',
                    'Courses plus longues le week-end pour développer l\'endurance',
                    'Augmentation du kilométrage hebdomadaire à un rythme contrôlé',
                ],
            ],
            'reduced_2' => [
                'weeks' => 'Semaine 8',
                'title' => 'Récupération mi-cycle (Réduite)',
                'description' => 'Récupération stratégique pour consolider les gains de forme',
                'points' => [
                    'Semaine de kilométrage réduit pour prévenir le surentraînement',
                    'Maintien de la fréquence, baisse de l\'intensité',
                    'Préparation pour la phase d\'entraînement de pointe',
                ],
            ],
            'development_3' => [
                'weeks' => 'Semaines 9-10',
                'title' => 'Phase d\'intensification (Développement)',
                'description' => 'Semaines de volume et d\'intensité les plus élevés du plan',
                'points' => [
                    'Courses les plus longues du cycle d\'entraînement (jusqu\'à 32 km)',
                    'Intensité d\'entraînement spécifique à la course',
                    'Développement de la résistance mentale',
                ],
            ],
            'reduced_3' => [
                'weeks' => 'Semaine 11',
                'title' => 'Récupération pré-maintien (Réduite)',
                'description' => 'Récupération finale avant la phase de maintien',
                'points' => [
                    'Réduction stratégique du volume pour absorber les semaines de pointe',
                    'Réinitialisation pour la phase de préparation finale',
                    'Évaluation de la progression et ajustements',
                ],
            ],
            'maintain' => [
                'weeks' => 'Semaines 12-13',
                'title' => 'Phase de maintien',
                'description' => 'Maintien de la condition physique tout en préparant l\'affûtage',
                'points' => [
                    'Volume constant à 80-90% du pic',
                    'Intégration du travail à allure de course',
                    'Affinement de la stratégie du jour de course',
                ],
            ],
            'taper' => [
                'weeks' => 'Semaines 14-15',
                'title' => 'Période d\'affûtage',
                'description' => 'Réduction stratégique pour maximiser la performance le jour de la course',
                'points' => [
                    'Réduction progressive du volume (40-60%)',
                    'Maintien de l\'intensité tout en réduisant le volume',
                    'Optimisation de la récupération et chargement en glycogène',
                ],
            ],
            'race' => [
                'weeks' => 'Semaine 16',
                'title' => 'Semaine de course',
                'description' => 'Préparation finale et exécution de la course',
                'points' => [
                    'Course minimale avec concentration sur le repos',
                    'Préparation de la logistique du jour de course',
                    'Jour du marathon !',
                ],
            ],
        ],
    ],
    'beta_section' => [
        'title' => 'Version bêta',
        'message_1' => 'Zone 2 est actuellement en version bêta.',
        'message_2' => 'Certaines fonctionnalités sont encore en cours de développement.',
    ],
    'cta_section' => [
        'title' => 'Prêt à structurer votre progression ?',
        'create_now' => 'Créer mon plan maintenant',
        'sign_in' => 'Se connecter pour commencer',
        'log_in' => 'Se connecter',
        'create_account' => 'Créer un compte',
    ],
];