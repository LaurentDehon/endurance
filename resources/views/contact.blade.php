@extends('layouts.app')
@section('content')
<div class="contact-container mx-auto p-4 md:p-8 max-w-7xl min-h-[calc(100vh-var(--nav-height)-var(--footer-height,0px))]">
    <!-- Header Section -->
    <div class="{{ themeClass('card') }} backdrop-blur-lg rounded-xl shadow-lg mb-8 py-6 border">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-3xl md:text-4xl font-bold {{ themeClass('text-1') }} mb-3">Contact Us</h1>
            <p class="text-lg md:text-xl {{ themeClass('text-2') }} max-w-2xl mx-auto mb-4">
                We're here to help with any questions about your training calendar
            </p>
        </div>
    </div>

    <!-- Contact Form Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Main Contact Form -->
        <div class="md:col-span-2">
            <div class="section-card {{ themeClass('card') }} backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full {{ themeClass('button') }} flex items-center justify-center">
                        <i class="fas fa-envelope text-xl {{ themeClass('text-1') }}"></i>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-bold {{ themeClass('text-1') }}">Send a Message</h2>
                </div>
                
                <form class="contact-form mt-8 space-y-6" action="{{ route('contact.send') }}" method="POST">
                    @csrf

                    <div class="rounded-md space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium {{ themeClass('text-1') }} mb-1">Your Name</label>
                                <input id="name" name="name" type="text" required placeholder="Your name" class="input w-full h-10 px-4 py-2 rounded-lg {{ themeClass('input') }} placeholder-gray-400">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium {{ themeClass('text-1') }} mb-1">Email Address</label>
                                <input id="email" name="email" type="email" required placeholder="your@email.com" class="input w-full h-10 px-4 py-2 rounded-lg {{ themeClass('input') }} placeholder-gray-400">
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium {{ themeClass('text-1', 'text-white') }} mb-1">Subject</label>
                            <input id="subject" name="subject" type="text" required placeholder="Subject of the message" class="input w-full h-10 px-4 py-2 rounded-lg {{ themeClass('input') }} placeholder-gray-400">
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium {{ themeClass('text-1', 'text-white') }} mb-1">Your Message</label>
                            <textarea id="message" name="message" rows="6" required placeholder="Your message..." class="input w-full px-4 py-2 rounded-lg {{ themeClass('input') }} placeholder-gray-400"></textarea>
                        </div>
                    </div>

                    <div>
                        <button type="submit" 
                            class="group relative w-full flex justify-center py-3 px-4 text-sm font-medium rounded-md {{ themeClass('button') }}">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <i class="fas fa-paper-plane"></i>
                            </span>
                            Send the message
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Contact Info Sidebar -->
        <div class="md:col-span-1">
            <div class="section-card {{ themeClass('card') }} backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border mb-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full {{ themeClass('button') }} flex items-center justify-center">
                        <i class="fas fa-info text-xl {{ themeClass('text-1') }}"></i>
                    </div>
                    <h2 class="text-xl font-bold {{ themeClass('text-1') }}">Contact Info</h2>
                </div>
                
                <ul class="space-y-4">
                    <li class="flex items-center gap-3">
                        <div class="mt-1 {{ themeClass('text-1') }}"><i class="fas fa-envelope"></i></div>
                        <span class="{{ themeClass('text-1') }}">info@zone2.be</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="mt-1 {{ themeClass('text-1') }}"><i class="fas fa-phone"></i></div>
                        <span class="{{ themeClass('text-1') }}">+32 000/00.00.00</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="mt-1 {{ themeClass('text-1') }}"><i class="fas fa-clock"></i></div>
                        <span class="{{ themeClass('text-1') }}">Mon-Fri: 9:00 AM - 5:00 PM</span>
                    </li>
                </ul>
            </div>
            
            <div class="section-card {{ themeClass('card') }} backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full {{ themeClass('button') }} flex items-center justify-center">
                        <i class="fas fa-share-alt text-xl {{ themeClass('text-1') }}"></i>
                    </div>
                    <h2 class="text-xl font-bold {{ themeClass('text-1') }}">Follow Us</h2>
                </div>
                
                <div class="flex justify-center gap-6 my-4">
                    <a href="#" class="w-10 h-10 rounded-full {{ themeClass('button') }} flex items-center justify-center hover:opacity-80 transition-opacity duration-300">
                        <i class="fab fa-twitter {{ themeClass('text-1') }}"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full {{ themeClass('button') }} flex items-center justify-center hover:opacity-80 transition-opacity duration-300">
                        <i class="fab fa-facebook {{ themeClass('text-1') }}"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full {{ themeClass('button') }} flex items-center justify-center hover:opacity-80 transition-opacity duration-300">
                        <i class="fab fa-instagram {{ themeClass('text-1') }}"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FAQ Section -->
    <div class="section-card {{ themeClass('card') }} backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border mt-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-full {{ themeClass('button') }} flex items-center justify-center">
                <i class="fas fa-question text-xl {{ themeClass('text-1') }}"></i>
            </div>
            <h2 class="text-2xl md:text-3xl font-bold {{ themeClass('text-1') }}">Frequently Asked Questions</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="{{ themeClass('card') }} p-5 rounded-xl">
                <h3 class="text-lg font-semibold {{ themeClass('text-1') }} mb-2">How quickly will I receive a response?</h3>
                <p class="{{ themeClass('text-2') }}">
                    We aim to respond to all inquiries within 24-48 hours during business days.
                </p>
            </div>
            
            <div class="{{ themeClass('card') }} p-5 rounded-xl">
                <h3 class="text-lg font-semibold {{ themeClass('text-1') }} mb-2">Do you offer technical support?</h3>
                <p class="{{ themeClass('text-2') }}">
                    Yes, our team provides technical assistance for any issues related to the training calendar.
                </p>
            </div>
            
            <div class="{{ themeClass('card') }} p-5 rounded-xl">
                <h3 class="text-lg font-semibold {{ themeClass('text-1') }} mb-2">How do I report bugs?</h3>
                <p class="{{ themeClass('text-2') }}">
                    Please use this contact form and include as many details as possible about the issue you're experiencing.
                </p>
            </div>
            
            <div class="{{ themeClass('card') }} p-5 rounded-xl">
                <h3 class="text-lg font-semibold {{ themeClass('text-1') }} mb-2">Can I request new features?</h3>
                <p class="{{ themeClass('text-2') }}">
                    Absolutely! We welcome feature suggestions and continuously improve based on user feedback.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    html, body {
        height: 100%;
        overflow-y: auto;
        margin: 0;
        padding: 0;
    }
    
    .contact-container {
        overflow-y: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    
    .contact-container::-webkit-scrollbar {
        display: none;
    }
    
    .contact-header {
        position: relative;
        padding: 3rem 0;
        overflow: hidden;
        border-radius: 1rem;
    }
    
    .contact-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.7) 0%, rgba(16, 24, 39, 0.8) 100%);
        z-index: -2;
    }
    
    .contact-form input:focus, 
    .contact-form textarea:focus {
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    }
    
    .input {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
    }
    
    .input:focus, .input:active, .input:hover {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        border-width: 0 !important;
        border-color: transparent !important;
        ring-width: 0 !important;
    }
    
    .contact-card {
        transition: transform 0.3s ease;
        position: relative;
    }
    
    .contact-card:hover {
        transform: translateY(-5px);
    }
    
    .section-card {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .section-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transform: translateX(-100%);
        transition: transform 1s ease;
    }
    
    .section-card:hover::after {
        transform: translateX(100%);
    }
    
    .section-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection