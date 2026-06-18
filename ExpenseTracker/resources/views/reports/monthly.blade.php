<x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@php
    $prevMonth = $month == 1 ? 12 : $month - 1;
    $prevYear  = $month == 1 ? $year - 1 : $year;
    $nextMonth = $month == 12 ? 1 : $month + 1;
    $nextYear  = $month == 12 ? $year + 1 : $year;
    $isCurrentMonth = ($month == now()->month && $year == now()->year);
    $monthName = \Carbon\Carbon::create()->month($month)->format('F');
    $monthValue = sprintf('%04d-%02d', $year, $month);
    $maxMonth   = now()->format('Y-m');
@endphp

<a href="{{ route('reports.index') }}" class="back-link">&#8592; Reports</a>

{{-- Month picker row --}}
<div style="background: #fff; border-radius: 12px; padding: 12px 16px; margin-bottom: 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
        <a href="{{ route('reports.monthly', ['month' => $prevMonth, 'year' => $prevYear]) }}"
           style="color: #4f46e5; font-size: 20px; text-decoration: none; padding: 4px 10px; background: #f0f2f5; border-radius: 8px;">&#8592;</a>
        <span style="font-size: 16px; font-weight: 700; color: #1a1a2e;">{{ $monthName }} {{ $year }}</span>
        @if (!$isCurrentMonth)
            <a href="{{ route('reports.monthly', ['month' => $nextMonth, 'year' => $nextYear]) }}"
               style="color: #4f46e5; font-size: 20px; text-decoration: none; padding: 4px 10px; background: #f0f2f5; border-radius: 8px;">&#8594;</a>
        @else
            <span style="padding: 4px 10px; color: #d1d5db; font-size: 20px; background: #f9fafb; border-radius: 8px;">&#8594;</span>
        @endif
    </div>

    {{-- Direct month/year picker --}}
    <form method="GET" action="{{ route('reports.monthly') }}" style="display: flex; gap: 8px; align-items: center;">
        <input type="month" name="monthpicker"
               id="monthpicker"
               value="{{ $monthValue }}"
               max="{{ $maxMonth }}"
               style="flex: 1; border: 1.5px solid #e5e7eb; border-radius: 10px; padding: 9px 12px; font-size: 14px; color: #1a1a2e; outline: none; font-family: inherit; background: #f9fafb;">
        <button type="submit" class="btn-primary" style="width: auto; padding: 10px 18px; font-size: 14px;">Go</button>
    </form>
</div>

{{-- Summary cards --}}
<div style="display: flex; gap: 10px; margin-bottom: 14px;">
    <div style="flex: 1; background: #4f46e5; border-radius: 14px; padding: 16px; color: #fff; text-align: center;">
        <div style="font-size: 11px; opacity: 0.8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px;">Total</div>
        <div style="font-size: 20px; font-weight: 800; margin-top: 4px;">&#8377;{{ number_format($monthlyTotal, 0) }}</div>
    </div>
    <div style="flex: 1; background: #fff; border-radius: 14px; padding: 16px; text-align: center; box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
        <div style="font-size: 11px; color: #9ca3af; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px;">Daily Avg</div>
        <div style="font-size: 20px; font-weight: 800; color: #1a1a2e; margin-top: 4px;">&#8377;{{ number_format($dailyAverage, 0) }}</div>
    </div>
</div>

@if ($categoryTotals->isEmpty())
    <div class="card" style="text-align: center; color: #9ca3af; padding: 40px 16px;">
        <p style="font-size: 14px;">No expenses for this month.</p>
    </div>
@else

    {{-- Chart --}}
    <div class="card" style="padding: 16px; margin-bottom: 14px;">
        <div class="card-title">Spending by Category</div>
        <div style="height: 220px;">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>

    {{-- Category breakdown --}}
    <div class="section-heading">Breakdown</div>

    @php
    $catColors = ['food'=>'#f59e0b','transportation'=>'#3b82f6','entertainment'=>'#8b5cf6','health'=>'#22c55e','shopping'=>'#ec4899','utilities'=>'#06b6d4','other'=>'#9ca3af'];
    @endphp

    <div class="card" style="padding: 0;">
        @foreach ($categoryTotals->sortByDesc('total') as $row)
            @php
                $pct   = $monthlyTotal > 0 ? ($row->total / $monthlyTotal) * 100 : 0;
                $color = $catColors[$row->category] ?? '#9ca3af';
            @endphp
            <div style="padding: 12px 16px; border-bottom: 1px solid #f3f4f6;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <div>
                        <span style="font-size: 14px; font-weight: 600; color: #1a1a2e;">{{ ucfirst($row->category) }}</span>
                        <span style="font-size: 12px; color: #9ca3af; margin-left: 6px;">{{ $row->count }} items</span>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 14px; font-weight: 700; color: #1a1a2e;">&#8377;{{ number_format($row->total, 0) }}</div>
                        <div style="font-size: 11px; color: #9ca3af;">{{ number_format($pct, 0) }}%</div>
                    </div>
                </div>
                <div style="background: #f3f4f6; border-radius: 4px; height: 4px; overflow: hidden;">
                    <div style="height: 100%; border-radius: 4px; background: {{ $color }}; width: {{ $pct }}%;"></div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        // Parse the month picker value and redirect with month/year params
        document.querySelector('form[action="{{ route('reports.monthly') }}"]').addEventListener('submit', function(e) {
            e.preventDefault();
            const val = document.getElementById('monthpicker').value; // "YYYY-MM"
            if (!val) return;
            const [y, m] = val.split('-');
            window.location.href = '{{ route('reports.monthly') }}?month=' + parseInt(m) + '&year=' + y;
        });

        const monthlyData = @json($categoryTotals->pluck('total', 'category'));
        const barColors = @json($catColors);

        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(monthlyData).map(l => l.charAt(0).toUpperCase() + l.slice(1)),
                datasets: [{
                    data: Object.values(monthlyData),
                    backgroundColor: Object.keys(monthlyData).map(k => barColors[k] || '#9ca3af'),
                    borderRadius: 6,
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' },
                        ticks: { callback: v => '₹' + v.toLocaleString(), font: { size: 11 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    }
                }
            }
        });
    </script>

@endif

</x-app-layout>
