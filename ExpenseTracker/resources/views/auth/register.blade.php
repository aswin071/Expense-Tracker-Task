<x-guest-layout>

    <div class="text-center mb-8">
        <div class="text-5xl mb-3">💰</div>
        <h1 class="text-2xl font-bold text-gray-900">Create account</h1>
        <p class="text-gray-400 text-sm mt-1">Start tracking your expenses today</p>
    </div>

    <form method="POST" action="{{ route('register') }}"
          class="space-y-4"
          x-data="{ submitting: false }"
          @submit="submitting = true">
        @csrf

        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Full name</label>
            <input id="name"
                   type="text"
                   name="name"
                   value="{{ old('name') }}"
                   required
                   autofocus
                   autocomplete="name"
                   placeholder="Your name"
                   class="w-full border rounded-2xl px-4 py-4 text-base focus:outline-none focus:ring-2 focus:ring-indigo-500
                          @error('name') border-red-400 bg-red-50 @else border-gray-300 bg-white @enderror">
            @error('name')
                <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email address</label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autocomplete="username"
                   inputmode="email"
                   placeholder="you@example.com"
                   class="w-full border rounded-2xl px-4 py-4 text-base focus:outline-none focus:ring-2 focus:ring-indigo-500
                          @error('email') border-red-400 bg-red-50 @else border-gray-300 bg-white @enderror">
            @error('email')
                <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Password <span class="text-gray-400 font-normal">(min. 8 characters)</span>
            </label>
            <input id="password"
                   type="password"
                   name="password"
                   required
                   autocomplete="new-password"
                   class="w-full border rounded-2xl px-4 py-4 text-base focus:outline-none focus:ring-2 focus:ring-indigo-500
                          @error('password') border-red-400 bg-red-50 @else border-gray-300 bg-white @enderror">
            @error('password')
                <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Confirm password
            </label>
            <input id="password_confirmation"
                   type="password"
                   name="password_confirmation"
                   required
                   autocomplete="new-password"
                   class="w-full border rounded-2xl px-4 py-4 text-base focus:outline-none focus:ring-2 focus:ring-indigo-500
                          @error('password_confirmation') border-red-400 bg-red-50 @else border-gray-300 bg-white @enderror">
            @error('password_confirmation')
                <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                :disabled="submitting"
                class="w-full bg-indigo-600 text-white font-bold py-4 rounded-2xl text-base min-h-[56px]
                       disabled:opacity-60 flex items-center justify-center gap-2 mt-2">
            <span x-show="!submitting">Create account</span>
            <span x-show="submitting" x-cloak class="flex items-center gap-2">
                <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                Creating account…
            </span>
        </button>

        <p class="text-center text-sm text-gray-500 pt-2">
            Already have an account?
            <a href="{{ route('login') }}" class="text-indigo-600 font-semibold">Sign in</a>
        </p>
    </form>

</x-guest-layout>
