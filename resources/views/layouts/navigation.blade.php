<nav x-data="{ open: false }" class="bg-cream/95 backdrop-blur border-b border-ink/10 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ Auth::check() ? route('dashboard') : route('productos.index') }}" class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-gold"></span>
                        <span class="font-display font-bold text-lg text-ink tracking-tight">Proyecto Web</span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @endauth

                    <x-nav-link :href="route('productos.index')" :active="request()->routeIs('productos.*')">
                        {{ __('Productos') }}
                    </x-nav-link>

                    @auth
                        <x-nav-link :href="route('carrito.index')" :active="request()->routeIs('carrito.*')">
                            {{ __('Carrito') }}
                        </x-nav-link>

                        <x-nav-link :href="route('reportes.index')" :active="request()->routeIs('reportes.*')">
                            {{ __('Reportes') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-ink/60 hover:text-ink focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-ink/60 hover:text-ink">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="ms-4 btn-primary">Registrarse</a>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-ink/50 hover:text-ink hover:bg-ink/5 focus:outline-none focus:bg-ink/5 focus:text-ink transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-cream border-t border-ink/10">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @endauth

            <x-responsive-nav-link :href="route('productos.index')" :active="request()->routeIs('productos.*')">
                {{ __('Productos') }}
            </x-responsive-nav-link>

            @auth
                <x-responsive-nav-link :href="route('carrito.index')" :active="request()->routeIs('carrito.*')">
                    {{ __('Carrito') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('reportes.index')" :active="request()->routeIs('reportes.*')">
                    {{ __('Reportes') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        <div class="pt-4 pb-1 border-t border-ink/10">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-ink">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-ink/50">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="px-4 space-y-2">
                    <a href="{{ route('login') }}" class="block text-ink/70">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="block text-forest font-medium">Registrarse</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
