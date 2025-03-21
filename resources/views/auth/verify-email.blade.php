@extends('layouts.guest')
@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-lg">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-bold text-gray-900">
                Email Verification
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                A verification link has been sent to your email address
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                A new verification link has been sent
            </div>
        @endif

        <div class="mt-8 text-center space-y-6">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" 
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg 
                           bg-blue-600 hover:bg-blue-700 
                           text-white font-medium shadow-sm transition-all duration-200">
                    Resend Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                    class="text-sm text-blue-600 hover:text-blue-500 transition-colors duration-200">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>
@endsection