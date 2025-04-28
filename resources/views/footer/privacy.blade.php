@extends('layouts.app')

@section('title', 'Privacy Policy - Zone 2')
@section('meta_description', 'Privacy Policy for Zone 2, the running training plan application. Learn about how we collect, use, and protect your personal data.')
@section('meta_keywords', 'privacy policy, data protection, privacy, zone 2, running app, personal data')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-8 shadow-lg border">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-shield-alt text-xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white">Privacy Policy</h1>
            </div>

            <div class="prose prose-lg prose-invert max-w-none">
                <p class="text-cyan-200">Last Updated: {{ date('F d, Y') }}</p>
                
                <h2 class="text-2xl font-bold text-white mt-8 mb-4">1. Introduction</h2>
                <p class="text-white">
                    At Zone 2 ("we", "us", or "our"), we respect your privacy and are committed to protecting your personal data. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our running training plan application.
                </p>
                <p class="text-white mt-4">
                    Please read this Privacy Policy carefully. If you do not agree with the terms of this Privacy Policy, please do not access the Service.
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">2. Information We Collect</h2>
                <p class="text-white">
                    We collect several types of information from and about users of our Service:
                </p>
                <h3 class="text-xl font-bold text-white mt-6 mb-3">2.1 Personal Data</h3>
                <p class="text-white">
                    When you register an account or use our Service, we may collect personally identifiable information such as:
                </p>
                <ul class="list-disc pl-6 text-white space-y-2">
                    <li>Your name and email address</li>
                    <li>Your account credentials</li>
                    <li>Your profile information</li>
                </ul>

                <h3 class="text-xl font-bold text-white mt-6 mb-3">2.2 Training Data</h3>
                <p class="text-white">
                    As part of our core functionality, we collect and process data related to your training activities:
                </p>
                <ul class="list-disc pl-6 text-white space-y-2">
                    <li>Running activities and workout details</li>
                    <li>Distance, duration, and elevation statistics</li>
                    <li>Training plans and goals</li>
                    <li>Performance metrics and progress data</li>
                </ul>

                <h3 class="text-xl font-bold text-white mt-6 mb-3">2.3 Strava Integration Data</h3>
                <p class="text-white">
                    If you choose to connect your Strava account, we may collect:
                </p>
                <ul class="list-disc pl-6 text-white space-y-2">
                    <li>Strava authentication tokens</li>
                    <li>Activity data from Strava (with your permission)</li>
                    <li>Training history and metrics from your Strava account</li>
                </ul>

                <h3 class="text-xl font-bold text-white mt-6 mb-3">2.4 Technical Data</h3>
                <p class="text-white">
                    We automatically collect certain information when you visit, use, or navigate our Service:
                </p>
                <ul class="list-disc pl-6 text-white space-y-2">
                    <li>Device and connection information (IP address, browser type, operating system)</li>
                    <li>Usage patterns and preferences</li>
                    <li>Log data and error reports</li>
                </ul>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">3. How We Use Your Information</h2>
                <p class="text-white">
                    We use the information we collect for various purposes, including:
                </p>
                <ul class="list-disc pl-6 text-white space-y-2">
                    <li>To provide and maintain our Service</li>
                    <li>To create and manage your account</li>
                    <li>To deliver the features of our training plan application</li>
                    <li>To process and analyze your training data</li>
                    <li>To improve our Service and user experience</li>
                    <li>To communicate with you about updates or changes to our Service</li>
                    <li>To detect, prevent, and address technical issues</li>
                </ul>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">4. Sharing Your Information</h2>
                <p class="text-white">
                    We may share your information in the following situations:
                </p>
                <ul class="list-disc pl-6 text-white space-y-2">
                    <li><strong>With Service Providers:</strong> We may share your information with third-party vendors, service providers, and other partners who help us provide our Service.</li>
                    <li><strong>With Your Consent:</strong> We may share your information when you specifically authorize us to do so.</li>
                    <li><strong>For Legal Reasons:</strong> We may disclose your information to comply with applicable laws and regulations, to respond to a subpoena, search warrant, or other lawful request.</li>
                    <li><strong>To Protect Rights:</strong> We may disclose information to protect the rights, property, or safety of Zone 2, our users, or others.</li>
                </ul>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">5. Third-Party Services</h2>
                <p class="text-white">
                    Our Service integrates with third-party services, particularly Strava. When you connect your Strava account to our Service, the data transfer is subject to Strava's privacy policy. We recommend reviewing their privacy policy to understand how they handle your information.
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">6. Data Security</h2>
                <p class="text-white">
                    We implement appropriate technical and organizational measures to protect your personal information. However, no method of transmission over the Internet or electronic storage is completely secure, and we cannot guarantee absolute security.
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">7. Your Data Protection Rights</h2>
                <p class="text-white">
                    Depending on your location, you may have certain rights regarding your personal information:
                </p>
                <ul class="list-disc pl-6 text-white space-y-2">
                    <li><strong>Access:</strong> You have the right to request copies of your personal data.</li>
                    <li><strong>Rectification:</strong> You have the right to request that we correct inaccurate information about you.</li>
                    <li><strong>Erasure:</strong> You have the right to request that we delete your personal data under certain conditions.</li>
                    <li><strong>Restriction:</strong> You have the right to request that we restrict the processing of your data under certain conditions.</li>
                    <li><strong>Data Portability:</strong> You have the right to request that we transfer the data we have collected to another organization or directly to you under certain conditions.</li>
                </ul>
                <p class="text-white mt-4">
                    To exercise any of these rights, please contact us through our <a href="{{ route('contact.show') }}" class="text-cyan-300 hover:underline">Contact page</a>.
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">8. Cookies and Similar Technologies</h2>
                <p class="text-white">
                    We use cookies and similar tracking technologies to track activity on our Service and hold certain information. Cookies are files with a small amount of data that may include an anonymous unique identifier.
                </p>
                <p class="text-white mt-4">
                    You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However, if you do not accept cookies, you may not be able to use some portions of our Service.
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">9. Children's Privacy</h2>
                <p class="text-white">
                    Our Service is not intended for use by children under the age of 16. We do not knowingly collect personally identifiable information from children under 16. If you are a parent or guardian and you are aware that your child has provided us with personal data, please contact us.
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">10. Changes to This Privacy Policy</h2>
                <p class="text-white">
                    We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last Updated" date. You are advised to review this Privacy Policy periodically for any changes.
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">11. Contact Us</h2>
                <p class="text-white">
                    If you have any questions about this Privacy Policy, please contact us through our <a href="{{ route('contact.show') }}" class="text-cyan-300 hover:underline">Contact page</a>.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection