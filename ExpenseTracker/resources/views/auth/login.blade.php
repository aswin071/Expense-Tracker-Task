<x-guest-layout>

    <div class="text-center mb-8">
        <div class="text-5xl mb-3">💰</div>
        <h1 class="text-2xl font-bold text-gray-900">Welcome back</h1>
        <p class="text-gray-400 text-sm mt-1">Sign in to ExpenseTracker</p>
    </div>

    @if (session('status'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl px-4 py-3 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}"
          class="space-y-4"
          x-data="{ submitting: false }"
          @submit="submitting = true">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email address</label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   autocomplete="username"
                   inputmode="email"
                   class="w-full border rounded-2xl px-4 py-4 text-base focus:outline-none focus:ring-2 focus:ring-indigo-500
                          @error('email') border-red-400 bg-red-50 @else border-gray-300 bg-white @enderror">
            @error('email')
                <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
            <input id="password"
                   type="password"
                   name="password"
                   required
                   autocomplete="current-password"
                   class="w-full border rounded-2xl px-4 py-4 text-base focus:outline-none focus:ring-2 focus:ring-indigo-500
                          @error('password') border-red-400 bg-red-50 @else border-gray-300 bg-white @enderror">
            @error('password')
                <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between pt-1">
            <label class="inline-flex items-center gap-2 text-sm text-gray-600 cursor-pointer min-h-[44px]">
                <input type="checkbox" name="remember" id="remember_me"
                       class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                Remember me
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-sm text-indigo-600 font-medium min-h-[44px] flex items-center">
                    Forgot password?
                </a>
            @endif
        </div>

        <button type="submit"
                :disabled="submitting"
                class="w-full bg-indigo-600 text-white font-bold py-4 rounded-2xl text-base min-h-[56px]
                       disabled:opacity-60 flex items-center justify-center gap-2">
            <span x-show="!submitting">Sign in</span>
            <span x-show="submitting" x-cloak class="flex items-center gap-2">
                <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                Signing in…
            </span>
        </button>

        <p class="text-center text-sm text-gray-500 pt-2">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-indigo-600 font-semibold">Create one</a>
        </p>
    </form>

</x-guest-layout>
