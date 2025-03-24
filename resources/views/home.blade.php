@extends('layouts.app')
@section('content')
    <div class="flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-5xl w-full mx-auto">
            <h2 class="text-2xl text-center font-bold text-gray-800 mb-10">Welcome to Endurance</h2>
            <p class="text-gray-600 mb-4">
                Thank you for participating in the beta phase of our Endurance app.
                Your role is crucial in helping us improve the user experience with your feedback and suggestions.
            </p>
            <p class="text-gray-600 mb-4">
                Endurance has been designed as a tool to help create personalized training plans.<br>
                While many runners rely on Excel spreadsheets to track their training programs, 
                our solution offers an automated and interactive alternative with advanced features.
                Therefore, think of Endurance more as an intelligent training log than just a training plan creator.
            </p>
            <p class="text-gray-600 mb-4">
                The Endurance app is inspired by a structured approach where your training is divided into blocks of weeks, with each week serving a specific purpose and composed of multiple workouts with clear objectives.
                By organizing your training in this way, you can focus on building a solid foundation, peaking at the right time, and avoiding overtraining.
                Each block is designed to progressively prepare you for your goal, ensuring that you are ready when the time comes for your race or event.
            </p>

            <div class="mb-4">
                <h3 class="font-semibold text-gray-800 mb-2">Why a Block-based Approach?</h3>
                <p class="text-gray-600 mb-4">
                    Effective training is not just about accumulating sessions. It relies on a structured periodization, where each week plays a specific role in your progress. Endurance draws inspiration from this method and allows you to:
                </p>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li>Plan your training weeks based on a specific goal (development, maintenance, recovery, etc.).</li>
                    <li>Structure your training load over several weeks to optimize progress and avoid overtraining.</li>
                    <li>Adapt each session to its role in the overall cycle, rather than seeing it as an isolated event.</li>
                </ul>
            </div>

            <div class="mb-4">
                <h3 class="font-semibold text-gray-800 mb-2">Key Features:</h3>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li>Interactive annual calendar to visualize and manage your training weeks</li>
                    <li>Ability to set goals (distance, duration, elevation) for each session</li>
                    <li>Automatic synchronization with Strava to import your activities</li>
                    <li>Dashboards comparing your actual performance to set goals</li>
                    <li>Ability to define training week types (reduced, development, etc.)</li>
                </ul>
            </div>
            <p class="text-gray-600 mb-4">
                <strong>Important Note:</strong> The app is currently in an English beta version.
                A full French localization is planned for the final release.
                Some features are still under development, and residual bugs may appear.
            </p>
            <div class="mb-4">
                <h3 class="font-semibold text-gray-800 mb-2">Example of Usage:</h3>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li>I start by creating a "training" on June 14th, representing my race goal.</li>
                    <li>To prepare, I define my training weeks, going backward in time:</li>
                    <ul class="list-disc pl-6 text-gray-600 space-y-2">
                        <li>The race week is set as a "Race" week.</li>
                        <li>The two weeks prior are "Taper" weeks, where I gradually reduce the training load.</li>
                        <li>The four weeks before that are "Maintain" weeks, representing the peak of training.</li>
                        <li>The previous weeks are "Development" weeks, focused on general physical conditioning.</li>
                        <li>Every 3 or 4 weeks, I integrate a "Reduced" week to prevent overtraining and allow for effective recovery.</li>
                        <li>During the development weeks, I make sure not to increase the training load by more than 10% per week.</li>
                    </ul>
                    <li>Depending on my goal (marathon, trail, 10k, etc.), I distribute my "trainings" each week to meet my weekly goals.</li>
                    <li>Personally, I prefer to set my weekly goals in terms of time rather than distance.</li>
                </ul>
            </div>            
            <p class="text-gray-600 mb-4 mt-10">
                We look forward to hearing your thoughts on:<br>
                - The usability of the interface<br>
                - The usefulness of the existing features<br>
                - Any suggestions for improvements<br>
                - Suggestions for the name (which is just a temporary choice) and the logo
            </p>
            <p class="text-gray-600 font-medium">
                Use the integrated contact form to share your feedback with us at any time.
            </p>
        </div>
    </div>
@endsection
