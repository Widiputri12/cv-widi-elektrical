<style>
    .nav-widi-border { border-bottom: 4px solid #D92323 !important; }
    .nav-widi-text-red { color: #D92323 !important; }
    .nav-widi-text-dark { color: #1A1A1A !important; }
    .nav-widi-text-yellow { color: #FFD700 !important; }
    .nav-widi-bg-dark { background-color: #1A1A1A !important; }
    
    /* Efek Garis Bawah Menu */
    .nav-link-widi {
        border-bottom: 2px solid transparent;
        color: #6B7280; /* Abu-abu bawaan */
        transition: all 0.3s;
    }
    .nav-link-widi:hover {
        color: #D92323 !important;
        border-bottom: 2px solid #D92323 !important;
    }
    .nav-link-widi.active {
        color: #D92323 !important;
        border-bottom: 2px solid #D92323 !important;
    }

    /* Tombol Profil Kanan Atas bergaya Retro/Timbul */
    .btn-widi-profile {
        background-color: #FFD700 !important;
        color: #1A1A1A !important;
        border: 2px solid #1A1A1A !important;
        box-shadow: 2px 2px 0px #1A1A1A !important;
        transition: all 0.2s ease-in-out !important;
    }
    .btn-widi-profile:hover {
        box-shadow: none !important;
        transform: translate(2px, 2px) !important;
        background-color: #e6c200 !important;
    }
</style>

<nav x-data="{ open: false }" class="bg-white nav-widi-border shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <img src="{{ asset('logo.png') }}" style="height: 40px; width: auto;" class="block drop-shadow-sm group-hover:scale-105 transition-transform" alt="Logo Widi" />
                        <div class="hidden md:flex flex-col justify-center">
                            <h1 class="font-black text-xl nav-widi-text-dark leading-none tracking-tight">CV. WIDI</h1>
                            <span class="text-[9px] font-black nav-widi-text-yellow nav-widi-bg-dark px-1.5 py-0.5 rounded uppercase tracking-widest mt-0.5 inline-block text-center">
                                Elektrical
                            </span>
                        </div>
                    </a>
                </div>

                {{-- MENU DESKTOP --}}
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-1 pt-1 font-bold text-sm leading-5 nav-link-widi {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>

                    {{-- MENU KHUSUS ADMIN --}}
                    @if(Auth::user()->role === 'admin')
                    
                    <a href="{{ route('admin.technicians.index') }}" 
                       class="inline-flex items-center px-1 pt-1 font-bold text-sm leading-5 nav-link-widi {{ request()->routeIs('admin.technicians.*') ? 'active' : '' }}">
                        Kelola Teknisi
                    </a>

                    <a href="{{ route('admin.galleries.index') }}" 
                       class="inline-flex items-center px-1 pt-1 font-bold text-sm leading-5 nav-link-widi {{ request()->routeIs('admin.galleries.index') ? 'active' : '' }}">
                        Kelola Galeri (Verifikasi)
                    </a>

                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 text-sm leading-4 font-black rounded-lg btn-widi-profile focus:outline-none">
                            <div class="uppercase">{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="font-bold text-gray-700 hover:text-[#D92323]">
                            {{ __('Profile Saya') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();" class="font-black nav-widi-text-red">
                                {{ __('Log Out Keluar') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-[#D92323] focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MENU MOBILE --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="font-bold nav-widi-text-dark">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            {{-- MENU MOBILE KHUSUS ADMIN --}}
            @if(Auth::user()->role === 'admin')
            
            <x-responsive-nav-link :href="route('admin.technicians.index')" :active="request()->routeIs('admin.technicians.*')" class="font-bold nav-widi-text-dark">
                Kelola Teknisi
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.galleries.index')" :active="request()->routeIs('admin.galleries.index')" class="font-bold nav-widi-text-dark">
                Kelola Galeri (Verifikasi)
            </x-responsive-nav-link>

            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-100 bg-gray-50">
            <div class="px-4">
                <div class="font-black text-base nav-widi-text-dark">{{ Auth::user()->name }}</div>
                <div class="font-bold text-xs text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="font-bold text-gray-700">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" class="font-black nav-widi-text-red">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>