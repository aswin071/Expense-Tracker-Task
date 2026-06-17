<x-app-layout>

    <div class="px-4 pt-4 pb-6 max-w-lg mx-auto space-y-4">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Budget Coach</h1>
                <p class="text-sm text-gray-400 mt-0.5">{{ now()->format('F Y') }} · vs your limits</p>
            </div>
            <a href="{{ route('coach.limits') }}"
               class="flex items-center gap-1.5 bg-indigo-50 text-indigo-700 text-sm font-semibold px-3 py-2 rounded-xl min-h-[44px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                Manage Limits
            </a>
        </div>

        @php
            $catIcons = ['food'=>'🍔','transportation'=>'🚗','entertainment'=>'🎬','health'=>'💊','shopping'=>'🛍️','utilities'=>'💡','other'=>'📦'];
        @endphp

        {{-- Summary banner --}}
        @php
            $overBudget = collect($categoryTotals)->filter(fn($t, $cat) => $t >= ($limits[$cat] ?? 5000) * 0.8)->count();
        @endphp
        @if ($overBudget > 0)
            <div class="bg-red-50 border-l-4 border-red-500 rounded-r-2xl px-4 py-3 flex items-center gap-3">
                <span class="text-2xl">⚠️</span>
                <p class="text-sm font-semibold text-red-700">
                    {{ $overBudget }} {{ Str::plural('category', $overBudget) }} at high spend this month.
                </p>
            </div>
        @else
            <div class="bg-green-50 border-l-4 border-green-500 rounded-r-2xl px-4 py-3 flex items-center gap-3">
                <span class="text-2xl">✅</span>
                <p class="text-sm font-semibold text-green-700">All categories within your limits.</p>
            </div>
        @endif

        {{-- Category cards --}}
        <div class="space-y-3">
            @foreach ($categoryTotals as $category => $total)
                @php
                    $limit     = $limits[$category] ?? 5000;
                    $pct       = $limit > 0 ? ($total / $limit) * 100 : 0;
                    $pctCapped = min($pct, 100);
                    $remaining = max($limit - $total, 0);

                    if ($pct >= 80) {
                        $border   = 'border-l-red-500';
                        $bar      = 'bg-red-500';
                        $badge    = 'bg-red-100 text-red-700';
                        $label    = 'High';
                        $amtColor = 'text-red-600';
                    } elseif ($pct >= 50) {
                        $border   = 'border-l-yellow-400';
                        $bar      = 'bg-yellow-400';
                        $badge    = 'bg-yellow-100 text-yellow-700';
                        $label    = 'Moderate';
                        $amtColor = 'text-yellow-600';
                    } else {
                        $border   = 'border-l-green-500';
                        $bar      = 'bg-green-500';
                        $badge    = 'bg-green-100 text-green-700';
                        $label    = 'On track';
                        $amtColor = 'text-green-600';
                    }
                @endphp

                <div class="bg-white rounded-2xl border border-gray-100 border-l-4 {{ $border }} shadow-sm px-4 py-4 space-y-3">

                    <div class="flex items-center gap-3">
                        <span class="text-2xl flex-shrink-0">{{ $catIcons[$category] ?? '📦' }}</span>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-gray-900 capitalize">{{ $category }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $badge }}">{{ $label }}</span>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-xl font-bold {{ $amtColor }}">₹{{ number_format($total, 0) }}</p>
                            <p class="text-xs text-gray-400">of ₹{{ number_format($limit, 0) }}</p>
                        </div>
                    </div>

                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                        <div class="{{ $bar }} h-2.5 rounded-full transition-all duration-500"
                             style="width: {{ $pctCapped }}%"></div>
                    </div>

                    <div class="flex justify-between text-xs text-gray-400">
                        <span>{{ number_format($pct, 0) }}% used</span>
                        @if ($remaining > 0)
                            <span class="text-green-600 font-medium">₹{{ number_format($remaining, 0) }} left</span>
                        @else
                            <span class="text-red-500 font-medium">₹{{ number_format(abs($total - $limit), 0) }} over</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <p class="text-xs text-gray-400 text-center pb-2">
            Tap "Manage Limits" to customise limits per category.
        </p>

    </div>

</x-app-layout>
