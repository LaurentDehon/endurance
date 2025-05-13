<?php

return [
    'page_title' => 'Privacy Policy - Zone 2',
    'meta_description' => 'Privacy policy for Zone 2, the running training plan application. Learn how we collect, use, and protect your personal data.',
    'meta_keywords' => 'privacy policy, data protection, privacy, zone 2, running app, personal data',
    'header' => [
        'title' => 'Privacy Policy'
    ],
    'last_updated' => 'Last updated: :date',
    'sections' => [
        'introduction' => [
            'title' => '1. Introduction',
            'content' => [
                'paragraph_1' => 'At Zone 2 ("we", "our", or "us"), we respect your privacy and are committed to protecting your personal data. This privacy policy explains how we collect, use, disclose, and safeguard your information when you use our running training plan application.',
                'paragraph_2' => 'Please read this privacy policy carefully. If you do not agree with the terms of this policy, please do not access the Service.'
            ]
        ],
        'information_collected' => [
            'title' => '2. Information We Collect',
            'content' => 'We collect several types of information from users of our Service:',
            'personal_data' => [
                'title' => '2.1 Personal Data',
                'content' => 'When you create an account or use our Service, we may collect personal information such as:',
                'items' => [
                    'name_email' => 'Your name and email address',
                    'credentials' => 'Your account credentials',
                    'profile' => 'Your profile information'
                ]
            ],
            'training_data' => [
                'title' => '2.2 Training Data',
                'content' => 'As part of our core functionality, we collect and process data related to your training activities:',
                'items' => [
                    'activities' => 'Running activities and workout details',
                    'statistics' => 'Distance, duration, and elevation statistics',
                    'plans' => 'Training plans and goals',
                    'metrics' => 'Performance metrics and progression data'
                ]
            ],
            'strava_data' => [
                'title' => '2.3 Strava Integration Data',
                'content' => 'If you choose to connect your Strava account, we may collect:',
                'items' => [
                    'tokens' => 'Strava authentication tokens',
                    'activity_data' => 'Activity data from Strava (with your permission)',
                    'history' => 'Training history and metrics from your Strava account'
                ]
            ],
            'technical_data' => [
                'title' => '2.4 Technical Data',
                'content' => 'We automatically collect certain information when you visit, use, or navigate our Service:',
                'items' => [
                    'device' => 'Device and connection information (IP address, browser type, operating system)',
                    'usage' => 'Usage habits and preferences',
                    'logs' => 'Log data and error reports'
                ]
            ]
        ],
        'how_we_use' => [
            'title' => '3. How We Use Your Information',
            'content' => 'We use the information we collect for various purposes, including:',
            'items' => [
                'provide' => 'To provide and maintain our Service',
                'manage' => 'To create and manage your account',
                'features' => 'To deliver the features of our training plan application',
                'analyze' => 'To process and analyze your training data',
                'improve' => 'To improve our Service and user experience',
                'communicate' => 'To communicate with you about updates or changes to our Service',
                'technical' => 'To detect, prevent, and address technical issues'
            ]
        ],
        'sharing' => [
            'title' => '4. Sharing Your Information',
            'content' => 'We may share your information in the following situations:',
            'items' => [
                'providers' => '<strong>With Service Providers:</strong> We may share your information with third-party vendors, service providers, and other partners who help us deliver our Service.',
                'consent' => '<strong>With Your Consent:</strong> We may share your information when you specifically allow us to do so.',
                'legal' => '<strong>For Legal Reasons:</strong> We may disclose your information to comply with applicable laws and regulations, to respond to a subpoena, search warrant, or other lawful request.',
                'rights' => '<strong>To Protect Rights:</strong> We may disclose information to protect the rights, property, or safety of Zone 2, our users, or others.'
            ]
        ],
        'third_party' => [
            'title' => '5. Third-Party Services',
            'content' => 'Our Service integrates with third-party services, particularly Strava. When you connect your Strava account to our Service, the data transfer is subject to Strava\'s privacy policy. We recommend reviewing their privacy policy to understand how they handle your information.'
        ],
        'security' => [
            'title' => '6. Data Security',
            'content' => 'We implement appropriate technical and organizational measures to protect your personal information. However, no method of transmission over the Internet or electronic storage is 100% secure, and we cannot guarantee absolute security.'
        ],
        'rights' => [
            'title' => '7. Your Data Protection Rights',
            'content' => 'Depending on your location, you may have certain rights regarding your personal information:',
            'items' => [
                'access' => '<strong>Access:</strong> You have the right to request copies of your personal data.',
                'rectification' => '<strong>Rectification:</strong> You have the right to request that we correct any inaccurate information about you.',
                'erasure' => '<strong>Erasure:</strong> You have the right to request that we delete your personal data in certain conditions.',
                'restriction' => '<strong>Restriction:</strong> You have the right to request that we restrict the processing of your data under certain conditions.',
                'portability' => '<strong>Data Portability:</strong> You have the right to request that we transfer the data we have collected to another organization or directly to you under certain conditions.'
            ],
            'exercise_rights' => 'To exercise any of these rights, please contact us via our <a href=":contact_url" class="text-cyan-300 hover:underline">contact page</a>.'
        ],
        'cookies' => [
            'title' => '8. Cookies and Similar Technologies',
            'content_1' => 'We use cookies and similar tracking technologies to track activity on our Service and store certain information. Cookies are files with a small amount of data that may include an anonymous unique identifier.',
            'content_2' => 'You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However, if you do not accept cookies, you may not be able to use some portions of our Service.'
        ],
        'children' => [
            'title' => '9. Children\'s Privacy',
            'content' => 'Our Service is not intended for children under 16 years of age. We do not knowingly collect personally identifiable information from children under 16. If you are a parent or guardian and you are aware that your child has provided us with personal data, please contact us.'
        ],
        'changes' => [
            'title' => '10. Changes to This Privacy Policy',
            'content' => 'We may update our privacy policy from time to time. We will notify you of any changes by posting the new privacy policy on this page and updating the "Last updated" date. We advise you to review this privacy policy periodically for any changes.'
        ],
        'contact' => [
            'title' => '11. Contact Us',
            'content' => 'If you have any questions about this privacy policy, please contact us through our <a href=":contact_url" class="text-cyan-300 hover:underline">contact page</a>.'
        ]
    ]
];