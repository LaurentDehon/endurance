@extends('layouts.app')

@section('title', 'Terms of Service - Zone 2')
@section('meta_description', 'Terms of Service for Zone 2, the running training plan application. Read about your rights and responsibilities when using our service.')
@section('meta_keywords', 'terms of service, terms and conditions, legal, zone 2, running app, terms of use')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-8 shadow-lg border">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-scroll text-xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white">Terms of Service</h1>
            </div>

            <div class="prose prose-lg prose-invert max-w-none">
                <p class="text-cyan-200">Last Updated: {{ date('F d, Y') }}</p>
                
                <h2 class="text-2xl font-bold text-white mt-8 mb-4">1. Introduction</h2>
                <p class="text-white">
                    Welcome to Zone 2 ("Service", "we", "us", or "our"), a running training plan application. By accessing or using our Service, you agree to be bound by these Terms of Service. If you disagree with any part of these terms, you may not access the Service.
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">2. Definitions</h2>
                <ul class="list-disc pl-6 text-white space-y-2">
                    <li><strong>Service:</strong> The Zone 2 application, website, and related services.</li>
                    <li><strong>User:</strong> Any individual who accesses or uses the Service.</li>
                    <li><strong>Content:</strong> Data and information created, uploaded, or otherwise made available by users or the Service.</li>
                    <li><strong>Training Data:</strong> Information related to your running activities, plans, and performance metrics.</li>
                </ul>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">3. User Accounts</h2>
                <p class="text-white">
                    To use certain features of our Service, you must register for an account. You are responsible for maintaining the security of your account and password. We cannot and will not be liable for any loss or damage from your failure to comply with this security obligation.
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">4. Service Usage</h2>
                <p class="text-white">
                    Our Service provides tools for creating and managing running training plans, tracking activities, and analyzing performance. The Service may include features for connecting with third-party services like Strava. You are responsible for maintaining necessary third-party accounts and complying with their terms of service.
                </p>
                <p class="text-white mt-4">
                    While we strive to provide useful training recommendations, you are ultimately responsible for your training decisions. Always consult with a healthcare professional before beginning any exercise program.
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">5. Acceptable Use</h2>
                <p class="text-white">
                    When using our Service, you agree not to:
                </p>
                <ul class="list-disc pl-6 text-white space-y-2">
                    <li>Use the Service in any way that could disable, overburden, or impair the Service</li>
                    <li>Use the Service for any illegal purpose or in violation of any laws</li>
                    <li>Attempt to gain unauthorized access to any part of the Service</li>
                    <li>Use the Service to send unsolicited communications</li>
                    <li>Interfere with other users' enjoyment of the Service</li>
                </ul>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">6. Intellectual Property</h2>
                <p class="text-white">
                    The Service and its original content, features, and functionality are owned by Zone 2 and are protected by international copyright, trademark, and other intellectual property laws. You retain ownership of any content you create or upload to the Service.
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">7. Third-Party Services</h2>
                <p class="text-white">
                    Our Service may integrate with third-party services, such as Strava. Your use of these integrations is subject to the respective terms of service of those third parties. We are not responsible for the content, policies, or practices of any third-party services.
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">8. Health Disclaimer</h2>
                <p class="text-white">
                    The training plans and advice provided by the Service are for informational purposes only and are not a substitute for professional medical advice. Always consult a qualified healthcare provider before beginning any new fitness program, particularly if you have any pre-existing health conditions.
                </p>
                <p class="text-white mt-4">
                    You acknowledge that there are risks associated with any physical activity and that you are solely responsible for your health and safety when following training plans created with our Service.
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">9. Limitation of Liability</h2>
                <p class="text-white">
                    To the maximum extent permitted by law, Zone 2 shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses resulting from your access to or use of or inability to access or use the Service.
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">10. Modifications to the Service</h2>
                <p class="text-white">
                    We reserve the right to modify or discontinue, temporarily or permanently, the Service (or any part thereof) with or without notice. We shall not be liable to you or to any third party for any modification, suspension, or discontinuance of the Service.
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">11. Changes to Terms</h2>
                <p class="text-white">
                    We reserve the right to modify these Terms at any time. We will provide notice of significant changes by updating the "Last Updated" date at the top of these Terms. Your continued use of the Service after such changes constitutes your acceptance of the new Terms.
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">12. Termination</h2>
                <p class="text-white">
                    We may terminate or suspend your account and access to the Service immediately, without prior notice or liability, for any reason, including without limitation if you breach these Terms. Upon termination, your right to use the Service will immediately cease.
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">13. Contact Us</h2>
                <p class="text-white">
                    If you have any questions about these Terms, please contact us through our <a href="{{ route('contact.show') }}" class="text-cyan-300 hover:underline">Contact page</a>.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection