<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#4f46e5">

    <title>{{ config('app.name', 'ExpenseTracker') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        body { padding-bottom: 80px; } /* space for bottom nav */
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

    {{-- Top bar --}}
    <header class="fixed top-0 inset-x-0 z-40 bg-white border-b border-gray-200 h-14 flex items-center justify-between px-4">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <span class="text-indigo-600 font-bold text-lg leading-none">💰</span>
            <span class="font-bold text-gray-800 text-base leading-none">ExpenseTracker</span>
        </a>

        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                    class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </button>

            <div x-show="open"
                 @click.outside="open = false"
                 x-cloak
                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-1 text-sm z-50">
                <div class="px-4 py-2 border-b border-gray-100">
                    <p class="font-medium text-gray-800 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-gray-400 text-xs truncate">{{ auth()->user()->email }}</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-gray-700">Profile Settings</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 text-red-600">Log out</button>
                </form>
            </div>
        </div>
    </header>

    {{-- Page content (push down below fixed top bar) --}}
    <main class="pt-14 min-h-screen">

        {{-- Flash messages --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-cloak
                 class="mx-4 mt-4 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-xl text-sm flex items-start gap-2">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="ml-auto text-green-600">✕</button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-cloak
                 class="mx-4 mt-4 bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-xl text-sm flex items-start gap-2">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('error') }}</span>
                <button @click="show = false" class="ml-auto text-red-600">✕</button>
            </div>
        @endif

        {{ $slot }}
    </main>

    {{-- Fixed bottom navigation bar --}}
    <nav class="fixed bottom-0 inset-x-0 z-40 bg-white border-t border-gray-200 h-16 safe-bottom">
        <div class="flex items-center justify-around h-full max-w-lg mx-auto px-2 relative">

            {{-- Home --}}
            <a href="{{ route('dashboard') }}"
               class="flex flex-col items-center justify-center gap-0.5 min-w-[48px] min-h-[48px] px-3
                      {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="text-[10px] font-medium">Home</span>
            </a>

            {{-- Expenses --}}
            <a href="{{ route('expenses.index') }}"
               class="flex flex-col items-center justify-center gap-0.5 min-w-[48px] min-h-[48px] px-3
                      {{ request()->routeIs('expenses.*') ? 'text-indigo-600' : 'text-gray-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span class="text-[10px] font-medium">Expenses</span>
            </a>

            {{-- FAB — Add expense --}}
            <a href="{{ route('expenses.create') }}"
               class="flex items-center justify-center w-14 h-14 rounded-full bg-indigo-600 text-white shadow-lg
                      -mt-6 border-4 border-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </a>

            {{-- Reports --}}
            <a href="{{ route('reports.index') }}"
               class="flex flex-col items-center justify-center gap-0.5 min-w-[48px] min-h-[48px] px-3
                      {{ request()->routeIs('reports.*') ? 'text-indigo-600' : 'text-gray-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="text-[10px] font-medium">Reports</span>
            </a>

            {{-- Coach --}}
            <a href="{{ route('coach.index') }}"
               class="flex flex-col items-center justify-center gap-0.5 min-w-[48px] min-h-[48px] px-3
                      {{ request()->routeIs('coach.*') ? 'text-indigo-600' : 'text-gray-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                <span class="text-[10px] font-medium">Coach</span>
            </a>

        </div>
    </nav>

</body>
</html>
