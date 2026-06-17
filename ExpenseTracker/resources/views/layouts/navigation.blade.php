<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Left: Logo + nav links --}}
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="shrink-0 flex items-center mr-8">
                    <span class="text-indigo-600 font-bold text-lg tracking-tight">💰 ExpenseTracker</span>
                </a>

                <div class="hidden sm:flex sm:items-center sm:gap-1">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>
                    <x-nav-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')">
                        Expenses
                    </x-nav-link>
                    <x-nav-link href="{{ url('/recurring') }}" :active="request()->is('recurring*')">
                        Recurring
                    </x-nav-link>
                    <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                        Reports
                    </x-nav-link>
                    <x-nav-link :href="route('coach.index')" :active="request()->routeIs('coach.index')">
                        Budget Coach
                    </x-nav-link>
                </div>
            </div>

            {{-- Right: User dropdown --}}
            <div class="hidden sm:flex sm:items-center sm:gap-3">
                <span class="text-sm text-gray-500">{{ Auth::user()->name }}</span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="text-sm text-gray-600 hover:text-red-600 border border-gray-300 hover:border-red-300
                                   px-3 py-1.5 rounded-lg transition">
                        Log out
                    </button>
                </form>
            </div>

            {{-- Hamburger (mobile) --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400
                               hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}"
                              class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}"
                              class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')">
                Expenses
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ url('/recurring') }}" :active="request()->is('recurring*')">
                Recurring
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                Reports
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('coach.index')" :active="request()->routeIs('coach.index')">
                Budget Coach
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-3 border-t border-gray-200 px-4">
            <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>

            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="text-sm text-red-600 hover:underline">Log out</button>
            </form>
        </div>
    </div>
</nav>
