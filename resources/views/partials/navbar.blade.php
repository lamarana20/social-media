<header class="bg-gray-900 sticky top-0 z-50">
    <nav
        x-data="{ 
            menuOpen: false, 
            searchOpen: false, 
            userOpen: false,
            searchQuery: new URLSearchParams(window.location.search).get('q') || '',
            searchTimeout: null,
            
            performSearch() {
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => {
                    if (this.searchQuery.trim().length > 0) {
                        window.location.href = '{{ route('posts.search') }}?q=' + encodeURIComponent(this.searchQuery);
                    }
                }, 300);
            }
        }"
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
    >
        <!-- TOP BAR -->
        <div class="flex items-center justify-between h-16">

            <!-- LEFT : Logo -->
            <div class="flex items-center">
                <a 
                    href="{{ route('posts.index') }}"
                    class="flex items-center gap-2 text-white font-bold text-lg hover:text-gray-300"
                >
                    <i class="fas fa-globe"></i>
                    <span>{{ config('app.name') }}</span>
                </a>
            </div>

            <!-- CENTER : Desktop Links & Search -->
            <div class="hidden md:flex items-center gap-6">
                <a 
                    href="{{ route('posts.index') }}"
                    class="text-gray-300 hover:text-white font-medium transition"
                >
                    Home
                </a>

                @auth
                    <a 
                        href="{{ route('dashboard') }}"
                        class="text-gray-300 hover:text-white font-medium transition"
                    >
                        Dashboard
                    </a>
                @endauth

                <!-- Search Bar (Desktop) -->
                <div class="relative">
                    <input 
                        type="text" 
                        x-model="searchQuery"
                        @input="performSearch()"
                        @keydown.enter.prevent="performSearch()"
                        placeholder="Search posts..."
                        class="w-64 px-4 py-2 pr-10 bg-gray-800 text-white border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-gray-400"
                    >
                    <button 
                        @click="performSearch()"
                        type="button"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition"
                    >
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- RIGHT : Profile or Login/Register -->
            <div class="flex items-center gap-4">
                <!-- Search Icon (Mobile) -->
                <button
                    @click="searchOpen = !searchOpen"
                    class="md:hidden text-white text-xl focus:outline-none"
                >
                    <i class="fas fa-search"></i>
                </button>

                @auth
                    <div class="relative hidden md:block">
                        <button
                            @click="userOpen = !userOpen"
                            class="flex items-center gap-2 text-white focus:outline-none"
                        >
                            <img
                                src="https://picsum.photos/seed/{{ auth()->id() }}/40"
                                alt="{{ auth()->user()->name }}"
                                class="w-9 h-9 rounded-full object-cover ring-2 ring-white"
                            >
                            <span class="hidden lg:inline font-medium">
                                {{ auth()->user()->name }}
                            </span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>

                        <!-- Dropdown -->
                        <div
                            x-show="userOpen"
                            x-cloak
                            @click.away="userOpen = false"
                            class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg overflow-hidden z-50"
                        >
                            <a 
                                href="{{ route('dashboard') }}"
                                class="block px-4 py-2 text-gray-800 hover:bg-gray-100"
                            >
                                Dashboard
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50"
                                >
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                @guest
                    <div class="hidden md:flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-white font-medium hover:text-gray-300">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white font-medium px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            Register
                        </a>
                    </div>
                @endguest

                <!-- Burger (mobile only) -->
                <button
                    @click="menuOpen = !menuOpen"
                    class="text-white text-xl focus:outline-none md:hidden"
                >
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <!-- MOBILE SEARCH BAR -->
        <div
            x-show="searchOpen"
            x-cloak
            x-transition
            class="md:hidden pb-4"
        >
            <div class="relative">
                <input 
                    type="text" 
                    x-model="searchQuery"
                    @input="performSearch()"
                    @keydown.enter.prevent="performSearch()"
                    placeholder="Search posts..."
                    class="w-full px-4 py-2 pr-10 bg-gray-800 text-white border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder-gray-400"
                >
                <button 
                    @click="performSearch()"
                    type="button"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition"
                >
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <!-- MOBILE MENU -->
        <div
            x-show="menuOpen"
            x-cloak
            class="md:hidden border-t border-gray-700 py-4 space-y-2"
        >
            <a 
                href="{{ route('posts.index') }}"
                class="block text-center py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg"
            >
                Home
            </a>

            @auth
                <a 
                    href="{{ route('dashboard') }}"
                    class="block text-center py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg"
                >
                    Dashboard
                </a>

                <form method="POST" action="{{ route('logout') }}" class="px-4">
                    @csrf
                    <button
                        type="submit"
                        class="w-full text-center py-2 text-red-400 hover:bg-gray-800 hover:text-red-300 rounded-lg"
                    >
                        Logout
                    </button>
                </form>
            @endauth

            @guest
                <a 
                    href="{{ route('login') }}"
                    class="block text-center py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg"
                >
                    Login
                </a>

                <a 
                    href="{{ route('register') }}"
                    class="block text-center py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-lg mx-4"
                >
                    Register
                </a>
            @endguest
        </div>
    </nav>
</header>