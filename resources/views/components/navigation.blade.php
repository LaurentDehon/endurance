{{-- Desktop Navigation Component --}}
<div class="hidden lg:ml-6 lg:flex lg:space-x-4">
    <a href="{{ route('home') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'text-white border-b-2 border-cyan-400' : 'text-slate-100 hover:border-b-2 border-cyan-500' }}">
        Home
    </a>
    <a href="{{ route('dashboard') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'text-white border-b-2 border-cyan-400' : 'text-slate-100 hover:border-b-2 border-cyan-500' }}">
        Dashboard
    </a>                                    
    <a href="{{ route('calendar') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('calendar') ? 'text-white border-b-2 border-cyan-400' : 'text-slate-100 hover:border-b-2 border-cyan-500' }}">
        Calendar
    </a>                                    
    <a href="{{ route('activities') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('activities') ? 'text-white border-b-2 border-cyan-400' : 'text-slate-100 hover:border-b-2 border-cyan-500' }}">
        Activities
    </a>
    <a href="{{ route('help') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('help') ? 'text-white border-b-2 border-cyan-400' : 'text-slate-100 hover:border-b-2 border-cyan-500' }}">
        Help
    </a>    
    <a href="{{ route('contact.show') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('contact.show') ? 'text-white border-b-2 border-cyan-400' : 'text-slate-100 hover:border-b-2 border-cyan-500' }}">
        Contact
    </a>                                                          
    @if (auth()->user()->is_admin)
        <a href="{{ route('admin') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin') ? 'text-white border-b-2 border-cyan-400' : 'text-slate-100 hover:border-b-2 border-cyan-500' }}">
            Admin
        </a>
    @endif
</div>