@extends('layouts.app')
@section('content')
<div class="flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 min-h-[calc(100vh-var(--nav-height)-var(--footer-height,0px))]">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-lg">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Contact us
            </h2>
        </div>
        <form class="mt-8 space-y-6" action="{{ route('contact.send') }}" method="POST">
            @csrf

            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <input id="name" name="name" type="text" required 
                        class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 
                            placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 
                            focus:border-blue-500 focus:z-10 sm:text-sm"
                        placeholder="Your name">
                </div>

                <div class="mt-4">
                    <input id="email" name="email" type="email" required 
                        class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 
                            placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 
                            focus:border-blue-500 focus:z-10 sm:text-sm"
                        placeholder="your@email.com">
                </div>

                <div class="mt-4">
                    <input id="subject" name="subject" type="text" required 
                        class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 
                            placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 
                            focus:border-blue-500 focus:z-10 sm:text-sm"
                        placeholder="Subject of the message">
                </div>

                <div class="mt-4">
                    <textarea id="message" name="message" rows="4" required 
                        class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 
                            placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 
                            focus:border-blue-500 focus:z-10 sm:text-sm"
                        placeholder="Your message..."></textarea>
                </div>
            </div>

            <div>
                <button type="submit" 
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent 
                        text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 
                        focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Send the message
                </button>
            </div>
        </form>
    </div>
</div>
@endsection