<x-app-layout>

    <div class="px-4 pt-4 pb-6 max-w-lg mx-auto space-y-4">

        {{-- Header --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('coach.index') }}"
               class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 text-gray-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Manage Limits</h1>
                <p class="text-sm text-gray-400">Set monthly budget per category</p>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-2xl px-4 py-3 text-sm font-medium flex items-center gap-2">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl px-4 py-3 text-sm text-indigo-700">
            💡 These limits are <strong>guidelines</strong> — not hard caps. They power the colour-coded progress bars on the Budget Coach page.
        </div>

        @php
            $catIcons = ['food'=>'🍔','transportation'=>'🚗','entertainment'=>'🎬','health'=>'💊','shopping'=>'🛍️','utilities'=>'💡','other'=>'📦'];
            $catLabels = ['food'=>'Food & Dining','transportation'=>'Transportation','entertainment'=>'Entertainment','health'=>'Health & Wellness','shopping'=>'Shopping','utilities'=>'Utilities & Bills','other'=>'Other'];
        @endphp

        <form method="POST" action="{{ route('coach.limits.update') }}" class="space-y-3">
            @csrf

            @foreach ($limits as $category => $amount)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-4">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-2xl">{{ $catIcons[$category] ?? '📦' }}</span>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $catLabels[$category] ?? ucfirst($category) }}</p>
                            <p class="text-xs text-gray-400">Default: ₹{{ number_format(\App\Models\BudgetLimit::DEFAULTS[$category] ?? 0) }}</p>
                        </div>
                    </div>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold text-base pointer-events-none">₹</span>
                        <input type="number"
                               name="limits[{{ $category }}]"
                               value="{{ old('limits.' . $category, $amount) }}"
                               min="100"
                               max="999999"
                               inputmode="numeric"
                               required
                               class="w-full border border-gray-200 rounded-xl pl-8 pr-4 py-3 text-base font-semibold text-gray-900
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500
                                      @error('limits.' . $category) border-red-400 bg-red-50 @enderror">
                        @error('limits.' . $category)
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @endforeach

            <button type="submit"
                    class="w-full bg-indigo-600 text-white font-bold py-4 rounded-2xl text-base min-h-[56px]
                           flex items-center justify-center gap-2 shadow-lg shadow-indigo-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save Limits
            </button>
        </form>

        {{-- Reset to defaults --}}
        <form method="POST" action="{{ route('coach.limits.reset') }}" class="pb-2"
              onsubmit="return confirm('Reset all limits back to defaults?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="w-full border border-gray-200 text-gray-500 font-medium py-3 rounded-2xl text-sm min-h-[44px]">
                Reset to defaults
            </button>
        </form>

    </div>

</x-app-layout>
