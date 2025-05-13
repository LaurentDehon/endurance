<?php

return [
    'meta' => [
        'title' => 'Zone 2 - Running Training Plan Application',
        'description' => 'Zone 2 helps you create personalized and structured training plans for running. Plan your weeks, sync with Strava, and optimize your progression.',
        'keywords' => 'running training plan, running planning, marathon, trail, periodization, zone 2, running progression, running calendar',
        'og_title' => 'Zone 2 - Take Control of Your Running Training',
        'og_description' => 'Create personalized training plans, structured in blocks and tailored to your goals. Analyze your performance and sync with Strava.',
    ],
    'hero' => [
        'title' => 'Take Control of Your Training',
        'description' => 'Zone 2 helps you create personalized, structured training plans tailored to your goals. A clear, progressive approach that you control.',
        'create_plan' => 'Create My Training Plan',
        'sign_in' => 'Sign In to Start',
        'discover' => 'Discover Features',
        'help' => 'Help Center',
    ],
    'why_section' => [
        'title' => 'Why Zone 2?',
        'description' => 'Many runners still plan their workouts in Excel spreadsheets or on paper. Zone 2 offers an interactive and structured alternative, designed for those who want to track their progress methodically.',
        'tagline' => 'Zone 2 isn\'t a plan generator, it\'s your smart training companion that evolves with you.',
    ],
    'structure_section' => [
        'title' => 'A Structure Designed for Progression',
        'subtitle' => 'Think in Blocks, Progress Intelligently',
        'description_1' => 'Zone 2 is based on structured periodization. Each week plays a specific role in your progression.',
        'description_2' => 'This method helps balance training load and optimize performance.',
        'week_types' => [
            'recovery' => [
                'title' => 'Recovery',
                'description' => 'A light intensity week focused on rest and active recovery',
            ],
            'development' => [
                'title' => 'Development',
                'description' => 'A high-load week designed to improve endurance, speed, or strength',
            ],
            'maintain' => [
                'title' => 'Maintain',
                'description' => 'A balanced week that maintains fitness without excessive stress',
            ],
            'reduced' => [
                'title' => 'Reduced',
                'description' => 'A week with reduced volume to prevent burnout and allow adaptation',
            ],
            'taper' => [
                'title' => 'Taper',
                'description' => 'A progressive reduction in workout volume before a race',
            ],
            'race' => [
                'title' => 'Race',
                'description' => 'Competition week including the race and recovery',
            ],
        ],
    ],
    'features_section' => [
        'title' => 'Key Features',
        'subtitle' => 'Everything You Need to Plan, Track, and Adjust',
        'features' => [
            'calendar' => [
                'title' => 'Interactive Calendar',
                'description' => 'View your plan by week and month with an intuitive interface',
            ],
            'goals' => [
                'title' => 'Precise Goal Setting',
                'description' => 'Set your targets in duration, distance, and elevation for each session',
            ],
            'strava' => [
                'title' => 'Strava Synchronization',
                'description' => 'Automatically import your activities to compare with your plan',
            ],
            'dashboards' => [
                'title' => 'Dashboards',
                'description' => 'Analyze your performance and compare planned vs. actual results',
            ],
            'week_types' => [
                'title' => 'Custom Week Types',
                'description' => 'Adapt your weeks according to your specific training phases',
            ],
            'overload_alert' => [
                'title' => 'Overload Alert',
                'description' => 'Receive notifications if you exceed the 10% increase rule',
            ],
            'block_structure' => [
                'title' => 'Coherent Block Structure',
                'description' => 'Organize your weeks based on your specific goals',
            ],
            'custom_plans' => [
                'title' => '100% Customized Plans',
                'description' => 'Create and adapt your plan according to your specific needs',
            ],
            'annual_planning' => [
                'title' => 'Clear Annual Planning',
                'description' => 'Visualize and organize your training year in just a few clicks',
            ],
        ],
    ],
    'how_section' => [
        'title' => 'How It Works',
        'subtitle' => 'Your Training in 5 Steps',
        'steps' => [
            'step_1' => [
                'title' => 'Create Your Goal',
                'description' => 'Define your main objective (e.g., Marathon, 50K Trail) and the event date',
            ],
            'step_2' => [
                'title' => 'Organize Your Weeks',
                'description' => 'Structure your weeks in blocks adapted to your goal (development, maintain, recovery...)',
            ],
            'step_3' => [
                'title' => 'Add Your Sessions',
                'description' => 'Plan your weekly sessions with details (type, distance, duration, elevation)',
            ],
            'step_4' => [
                'title' => 'Sync with Strava',
                'description' => 'Connect your Strava account to import your activities and compare with your plan',
            ],
            'step_5' => [
                'title' => 'Track and Adjust',
                'description' => 'Modify your plan based on how you feel and your actual performance',
            ],
        ],
    ],
    'training_blocks' => [
        'title' => '16-Week Marathon Training Plan Example',
        'tagline' => 'A strategic progression from initial development to race day, with regular recovery periods to optimize adaptation.',
        'blocks' => [
            'development_1' => [
                'weeks' => 'Weeks 1-3',
                'title' => 'Base Building (Development)',
                'description' => 'Development phase focused on building aerobic foundation',
                'points' => [
                    'Progressive volume increase week by week',
                    'Low-intensity foundation work to build endurance',
                    'Establishing running routine and consistency',
                ],
            ],
            'reduced_1' => [
                'weeks' => 'Week 4',
                'title' => 'First Recovery (Reduced)',
                'description' => 'Planned reduction in volume to allow adaptation',
                'points' => [
                    '20-30% volume reduction from previous weeks',
                    'Focus on active recovery sessions',
                    'Extra rest and sleep emphasis',
                ],
            ],
            'development_2' => [
                'weeks' => 'Weeks 5-7',
                'title' => 'Building Phase (Development)',
                'description' => 'Progressive development with increasing intensity',
                'points' => [
                    'Introduction of tempo runs and some speedwork',
                    'Longer weekend runs to build endurance',
                    'Building weekly mileage at a controlled rate',
                ],
            ],
            'reduced_2' => [
                'weeks' => 'Week 8',
                'title' => 'Mid-cycle Recovery (Reduced)',
                'description' => 'Strategic recovery to consolidate fitness gains',
                'points' => [
                    'Reduced mileage week to prevent overtraining',
                    'Maintain frequency, lower intensity',
                    'Preparation for peak training phase',
                ],
            ],
            'development_3' => [
                'weeks' => 'Weeks 9-10',
                'title' => 'Peak Training (Development)',
                'description' => 'Highest volume and intensity weeks of the plan',
                'points' => [
                    'Longest runs of training cycle (up to 20 miles)',
                    'Race-specific workout intensity',
                    'Mental toughness development',
                ],
            ],
            'reduced_3' => [
                'weeks' => 'Week 11',
                'title' => 'Pre-maintain Recovery (Reduced)',
                'description' => 'Final recovery before maintain phase',
                'points' => [
                    'Strategic volume reduction to absorb peak weeks',
                    'Reset for final preparation phase',
                    'Assessment of progress and adjustments',
                ],
            ],
            'maintain' => [
                'weeks' => 'Weeks 12-13',
                'title' => 'Maintain Phase',
                'description' => 'Holding fitness while preparing for taper',
                'points' => [
                    'Consistent volume at 80-90% of peak',
                    'Race pace work integration',
                    'Race day strategy refinement',
                ],
            ],
            'taper' => [
                'weeks' => 'Weeks 14-15',
                'title' => 'Taper Period',
                'description' => 'Strategic reduction to maximize race day performance',
                'points' => [
                    'Progressive volume reduction (40-60%)',
                    'Maintaining intensity while reducing volume',
                    'Recovery optimization and glycogen loading',
                ],
            ],
            'race' => [
                'weeks' => 'Week 16',
                'title' => 'Race Week',
                'description' => 'Final preparation and race execution',
                'points' => [
                    'Minimal running with rest focus',
                    'Race day logistics preparation',
                    'Marathon day!',
                ],
            ],
        ],
    ],
    'beta_section' => [
        'title' => 'Beta Version',
        'message_1' => 'Zone 2 is currently in beta version.',
        'message_2' => 'Some features are still under development.',
    ],
    'cta_section' => [
        'title' => 'Ready to Structure Your Progress?',
        'create_now' => 'Create My Plan Now',
        'sign_in' => 'Sign In to Get Started',
        'log_in' => 'Log in',
        'create_account' => 'Create an account',
    ],
];