<x-app-layout>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="px-4 pt-4 pb-6 max-w-lg mx-auto space-y-4">

        {{-- ← Month Year → arrow navigator --}}
        @php
            $prevMonth = $month == 1 ? 12 : $month - 1;
            $prevYear  = $month == 1 ? $year - 1 : $year;
            $nextMonth = $month == 12 ? 1 : $month + 1;
            $nextYear  = $month == 12 ? $year + 1 : $year;
            $isCurrentMonth = ($month == now()->month && $year == now()->year);
        @endphp

        <div class="flex items-center justify-between bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-3">
            <a href="{{ route('reports.monthly', ['month' => $prevMonth, 'year' => $prevYear]) }}"
               class="w-11 h-11 flex items-center justify-center rounded-xl bg-gray-100 text-gray-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            <div class="text-center">
                <p class="font-bold text-gray-900 text-lg">
                    {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                </p>
                <p class="text-sm text-gray-400">{{ $year }}</p>
            </div>

            <a href="{{ $isCurrentMonth ? '#' : route('reports.monthly', ['month' => $nextMonth, 'year' => $nextYear]) }}"
               class="w-11 h-11 flex items-center justify-center rounded-xl flex-shrink-0
                      {{ $isCurrentMonth ? 'bg-gray-50 text-gray-300 pointer-events-none' : 'bg-gray-100 text-gray-600' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        {{-- Summary cards — stacked vertically --}}
        <div class="grid grid-cols-1 gap-3">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-2xl px-5 py-4 flex items-center justify-between">
                <div>
                    <p class="text-indigo-200 text-xs uppercase tracking-wide">Total this month</p>
                    <p class="text-3xl font-bold mt-1">₹{{ number_format($monthlyTotal, 0) }}</p>
                </div>
                <div class="text-4xl opacity-30">💸</div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-3 text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Daily avg</p>
                    <p class="text-xl font-bold text-green-600 mt-1">₹{{ number_format($dailyAverage, 0) }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-3 text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Categories</p>
                    <p class="text-xl font-bold text-gray-800 mt-1">{{ $categoryTotals->count() }}</p>
                </div>
            </div>
        </div>

        @if ($categoryTotals->isEmpty())
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-10 text-center">
                <div class="text-6xl mb-4">📊</div>
                <p class="text-gray-700 font-semibold text-lg">No data for this month</p>
                <p class="text-gray-400 text-sm mt-2">
                    Try a different month, or
                    <a href="{{ route('expenses.create') }}" class="text-indigo-600 font-medium">log an expense</a>.
                </p>
            </div>
        @else

            {{-- Bar chart — full width, fixed 250px height --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <p class="text-sm font-semibold text-gray-700 mb-3">Spending by Category</p>
                <div style="height: 250px; position: relative;">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            {{-- Category progress bars (replaces table) --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-4 space-y-4">
                <p class="text-sm font-semibold text-gray-700">Category Breakdown</p>

                @php
                    $catIcons  = ['food'=>'🍔','transportation'=>'🚗','entertainment'=>'🎬','health'=>'💊','shopping'=>'🛍️','utilities'=>'💡','other'=>'📦'];
                    $maxAmount = $categoryTotals->max('total') ?: 1;
                @endphp

                @foreach ($categoryTotals->sortByDesc('total') as $row)
                    @php $barPct = min(($row->total / $maxAmount) * 100, 100); @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="text-base">{{ $catIcons[$row->category] ?? '📦' }}</span>
                                <span class="text-sm font-medium text-gray-700 capitalize">{{ $row->category }}</span>
                                <span class="text-xs text-gray-400">×{{ $row->count }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-bold text-gray-900">₹{{ number_format($row->total, 0) }}</span>
                                @if (isset($allTimeTotals[$row->category]))
                                    <span class="text-xs text-gray-400 ml-1">/ ₹{{ number_format($allTimeTotals[$row->category], 0) }} all‑time</span>
                                @endif
                            </div>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-indigo-500 h-2 rounded-full transition-all duration-500"
                                 style="width: {{ $barPct }}%"></div>
                        </div>
                    </div>
                @endforeach

                <div class="pt-3 border-t border-gray-100 flex justify-between text-sm">
                    <span class="font-semibold text-gray-700">Total</span>
                    <span class="font-bold text-indigo-700">₹{{ number_format($monthlyTotal, 2) }}</span>
                </div>
            </div>

            {{-- All-time chart --}}
            @if (!empty($allTimeTotals))
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                    <p class="text-sm font-semibold text-gray-700 mb-3">All-Time by Category</p>
                    <div style="height: 250px; position: relative;">
                        <canvas id="allTimeChart"></canvas>
                    </div>
                </div>
            @endif

        @endif

    </div>

    @if ($categoryTotals->isNotEmpty())
    <script>
        const monthlyData = @json($categoryTotals->pluck('total', 'category'));

        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(monthlyData).map(l => l.charAt(0).toUpperCase() + l.slice(1)),
                datasets: [{
                    data: Object.values(monthlyData),
                    backgroundColor: 'rgba(99,102,241,0.75)',
                    borderColor: 'rgba(99,102,241,1)',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => '₹' + v.toLocaleString() }
                    }
                }
            }
        });

        @if (!empty($allTimeTotals))
        const allTimeData = @json($allTimeTotals);
        new Chart(document.getElementById('allTimeChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(allTimeData).map(l => l.charAt(0).toUpperCase() + l.slice(1)),
                datasets: [{
                    data: Object.values(allTimeData),
                    backgroundColor: 'rgba(16,185,129,0.75)',
                    borderColor: 'rgba(16,185,129,1)',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => '₹' + v.toLocaleString() }
                    }
                }
            }
        });
        @endif
    </script>
    @endif

</x-app-layout>
