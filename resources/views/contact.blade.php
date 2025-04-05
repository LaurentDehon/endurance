@extends('layouts.app')
@section('content')
<style>
    html, body {
        height: 100%;
        overflow-y: auto;
        margin: 0;
        padding: 0;
    }
    
    .contact-container {
        overflow-y: auto;
        max-height: calc(100vh - var(--nav-height) - var(--footer-height));
        scrollbar-width: none; /* Hide scrollbars Firefox */
        -ms-overflow-style: none; /* Hide scrollbars IE and Edge */
    }
    
    .contact-container::-webkit-scrollbar {
        display: none; /* Hide scrollbars Chrome, Safari and Opera */
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

<div class="contact-container mx-auto p-4 md:p-8 max-w-7xl min-h-[calc(100vh-var(--nav-height)-var(--footer-height,0px))]">
    <!-- Header Section with Improved Design -->
    <div class="{{ themeClass('card-bg', 'bg-white bg-opacity-10') }} backdrop-blur-lg rounded-xl shadow-lg mb-8 py-6">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-3xl md:text-4xl font-bold {{ themeClass('text-primary', 'text-white') }} mb-3">Contact Us</h1>
            <p class="text-lg md:text-xl {{ themeClass('text-secondary', 'text-gray-200') }} max-w-2xl mx-auto mb-4">
                We're here to help with any questions about your training calendar
            </p>
        </div>
    </div>

    <!-- Contact Form Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Main Contact Form -->
        <div class="md:col-span-2">
            <div class="section-card {{ themeClass('card-bg', 'bg-white bg-opacity-10') }} backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border {{ themeClass('card-border', 'border-white border-opacity-20') }}">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full {{ themeClass('button-bg', 'bg-blue-600') }} flex items-center justify-center">
                        <i class="fas fa-envelope text-xl {{ themeClass('text-primary', 'text-white') }}"></i>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-bold {{ themeClass('text-primary', 'text-white') }}">Send a Message</h2>
                </div>
                
                <form class="contact-form mt-8 space-y-6" action="{{ route('contact.send') }}" method="POST">
                    @csrf

                    <div class="rounded-md space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium {{ themeClass('text-primary', 'text-white') }} mb-1">Your Name</label>
                                <input id="name" name="name" type="text" required 
                                    class="{{ themeClass('card-bg') }} appearance-none rounded relative block w-full px-3 py-3 border {{ themeClass('card-border') }}
                                        placeholder-gray-300 {{ themeClass('text-primary') }} focus:outline-none focus:ring-blue-500 
                                        focus:border-blue-500 focus:z-10 sm:text-sm"
                                    placeholder="Your name">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium {{ themeClass('text-primary', 'text-white') }} mb-1">Email Address</label>
                                <input id="email" name="email" type="email" required 
                                    class="{{ themeClass('card-bg') }} appearance-none rounded relative block w-full px-3 py-3 border {{ themeClass('card-border') }}
                                        placeholder-gray-300 {{ themeClass('text-primary') }} focus:outline-none focus:ring-blue-500 
                                        focus:border-blue-500 focus:z-10 sm:text-sm"
                                    placeholder="your@email.com">
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium {{ themeClass('text-primary', 'text-white') }} mb-1">Subject</label>
                            <input id="subject" name="subject" type="text" required 
                                class="{{ themeClass('card-bg') }} appearance-none rounded relative block w-full px-3 py-3 border {{ themeClass('card-border') }}
                                    placeholder-gray-300 {{ themeClass('text-primary') }} focus:outline-none focus:ring-blue-500 
                                    focus:border-blue-500 focus:z-10 sm:text-sm"
                                placeholder="Subject of the message">
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium {{ themeClass('text-primary', 'text-white') }} mb-1">Your Message</label>
                            <textarea id="message" name="message" rows="6" required 
                                class="{{ themeClass('card-bg') }} appearance-none rounded relative block w-full px-3 py-3 border {{ themeClass('card-border') }}
                                    placeholder-gray-300 {{ themeClass('text-primary') }} focus:outline-none focus:ring-blue-500 
                                    focus:border-blue-500 focus:z-10 sm:text-sm"
                                placeholder="Your message..."></textarea>
                        </div>
                    </div>

                    <div>
                        <button type="submit" 
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent 
                                text-sm font-medium rounded-md {{ themeClass('button-text') }} {{ themeClass('button-bg') }}
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300">
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
            <div class="section-card {{ themeClass('card-bg', 'bg-white bg-opacity-10') }} backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border {{ themeClass('card-border', 'border-white border-opacity-20') }} mb-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full {{ themeClass('button-bg', 'bg-blue-600') }} flex items-center justify-center">
                        <i class="fas fa-info text-xl {{ themeClass('text-primary', 'text-white') }}"></i>
                    </div>
                    <h2 class="text-xl font-bold {{ themeClass('text-primary', 'text-white') }}">Contact Info</h2>
                </div>
                
                <ul class="space-y-4">
                    <li class="flex items-center gap-3">
                        <div class="mt-1 {{ themeClass('text-primary', 'text-blue-400') }}"><i class="fas fa-envelope"></i></div>
                        <span class="{{ themeClass('text-primary', 'text-gray-200') }}">info@zone2.be</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="mt-1 {{ themeClass('text-primary', 'text-blue-400') }}"><i class="fas fa-phone"></i></div>
                        <span class="{{ themeClass('text-primary', 'text-gray-200') }}">+32 000/00.00.00</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="mt-1 {{ themeClass('text-primary', 'text-blue-400') }}"><i class="fas fa-clock"></i></div>
                        <span class="{{ themeClass('text-primary', 'text-gray-200') }}">Mon-Fri: 9:00 AM - 5:00 PM</span>
                    </li>
                </ul>
            </div>
            
            <div class="section-card {{ themeClass('card-bg', 'bg-white bg-opacity-10') }} backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border {{ themeClass('card-border', 'border-white border-opacity-20') }}">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full {{ themeClass('button-bg', 'bg-blue-600') }} flex items-center justify-center">
                        <i class="fas fa-share-alt text-xl {{ themeClass('text-primary', 'text-white') }}"></i>
                    </div>
                    <h2 class="text-xl font-bold {{ themeClass('text-primary', 'text-white') }}">Follow Us</h2>
                </div>
                
                <div class="flex justify-center gap-6 my-4">
                    <a href="#" class="w-10 h-10 rounded-full {{ themeClass('button-bg', 'bg-blue-600') }} flex items-center justify-center hover:opacity-80 transition-opacity duration-300">
                        <i class="fab fa-twitter {{ themeClass('text-primary', 'text-white') }}"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full {{ themeClass('button-bg', 'bg-blue-600') }} flex items-center justify-center hover:opacity-80 transition-opacity duration-300">
                        <i class="fab fa-facebook {{ themeClass('text-primary', 'text-white') }}"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full {{ themeClass('button-bg', 'bg-blue-600') }} flex items-center justify-center hover:opacity-80 transition-opacity duration-300">
                        <i class="fab fa-instagram {{ themeClass('text-primary', 'text-white') }}"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FAQ Section -->
    <div class="section-card {{ themeClass('card-bg', 'bg-white bg-opacity-10') }} backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border {{ themeClass('card-border', 'border-white border-opacity-20') }} mt-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-full {{ themeClass('button-bg', 'bg-blue-600') }} flex items-center justify-center">
                <i class="fas fa-question text-xl {{ themeClass('text-primary', 'text-white') }}"></i>
            </div>
            <h2 class="text-2xl md:text-3xl font-bold {{ themeClass('text-primary', 'text-white') }}">Frequently Asked Questions</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="{{ themeClass('card-bg', 'bg-white bg-opacity-5') }} p-5 rounded-xl">
                <h3 class="text-lg font-semibold {{ themeClass('text-primary', 'text-white') }} mb-2">How quickly will I receive a response?</h3>
                <p class="{{ themeClass('text-secondary', 'text-blue-200') }}">
                    We aim to respond to all inquiries within 24-48 hours during business days.
                </p>
            </div>
            
            <div class="{{ themeClass('card-bg', 'bg-white bg-opacity-5') }} p-5 rounded-xl">
                <h3 class="text-lg font-semibold {{ themeClass('text-primary', 'text-white') }} mb-2">Do you offer technical support?</h3>
                <p class="{{ themeClass('text-secondary', 'text-blue-200') }}">
                    Yes, our team provides technical assistance for any issues related to the training calendar.
                </p>
            </div>
            
            <div class="{{ themeClass('card-bg', 'bg-white bg-opacity-5') }} p-5 rounded-xl">
                <h3 class="text-lg font-semibold {{ themeClass('text-primary', 'text-white') }} mb-2">How do I report bugs?</h3>
                <p class="{{ themeClass('text-secondary', 'text-blue-200') }}">
                    Please use this contact form and include as many details as possible about the issue you're experiencing.
                </p>
            </div>
            
            <div class="{{ themeClass('card-bg', 'bg-white bg-opacity-5') }} p-5 rounded-xl">
                <h3 class="text-lg font-semibold {{ themeClass('text-primary', 'text-white') }} mb-2">Can I request new features?</h3>
                <p class="{{ themeClass('text-secondary', 'text-blue-200') }}">
                    Absolutely! We welcome feature suggestions and continuously improve based on user feedback.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection