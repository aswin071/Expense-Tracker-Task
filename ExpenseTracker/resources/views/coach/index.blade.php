<x-app-layout>

<div class="page-header">
    <h1 style="font-size: 20px; font-weight: 700;">Budget Coach</h1>
    <a href="{{ route('coach.limits') }}" class="btn-ghost" style="font-size: 13px; padding: 8px 14px;">Edit Limits</a>
</div>

<p style="font-size: 13px; color: #9ca3af; margin-bottom: 14px; margin-top: -8px;">{{ now()->format('F Y') }}</p>

@php
    $overBudget = collect($categoryTotals)->filter(fn($t, $cat) => $t >= ($limits[$cat] ?? 5000) * 0.8)->count();
    $catColors  = [
        'food'           => ['bg' => '#fef3c7', 'text' => '#92400e'],
        'transportation' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
        'entertainment'  => ['bg' => '#ede9fe', 'text' => '#5b21b6'],
        'health'         => ['bg' => '#dcfce7', 'text' => '#166534'],
        'shopping'       => ['bg' => '#fce7f3', 'text' => '#9d174d'],
        'utilities'      => ['bg' => '#e0f2fe', 'text' => '#0c4a6e'],
        'other'          => ['bg' => '#f3f4f6', 'text' => '#374151'],
    ];
@endphp

@if ($overBudget > 0)
    <div style="background: #fff7ed; border-left: 4px solid #f59e0b; border-radius: 10px; padding: 12px 14px; margin-bottom: 14px; font-size: 13px; color: #92400e; font-weight: 600;">
        &#9888; {{ $overBudget }} {{ $overBudget === 1 ? 'category is' : 'categories are' }} nearing the budget limit.
    </div>
@endif

<div class="card" style="padding: 4px 0;">
    @foreach ($categoryTotals as $category => $total)
        @php
            $limit = $limits[$category] ?? 5000;
            $pct   = $limit > 0 ? min(($total / $limit) * 100, 100) : 0;
            $c     = $catColors[$category] ?? $catColors['other'];

            if ($pct >= 80) {
                $statusLabel = 'High';
                $barColor    = '#ef4444';
                $statusColor = '#dc2626';
            } elseif ($pct >= 50) {
                $statusLabel = 'Moderate';
                $barColor    = '#f59e0b';
                $statusColor = '#d97706';
            } else {
                $statusLabel = 'On track';
                $barColor    = '#22c55e';
                $statusColor = '#16a34a';
            }
        @endphp
        <div style="padding: 14px 16px; border-bottom: 1px solid #f3f4f6;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: {{ $c['bg'] }}; color: {{ $c['text'] }}; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800; flex-shrink: 0;">
                    {{ strtoupper(substr($category, 0, 2)) }}
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 14px; font-weight: 600; color: #1a1a2e;">{{ ucfirst($category) }}</span>
                        <span style="font-size: 12px; font-weight: 700; color: {{ $statusColor }};">{{ $statusLabel }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 12px; color: #9ca3af; margin-top: 2px;">
                        <span>&#8377;{{ number_format($total, 0) }} spent</span>
                        <span>of &#8377;{{ number_format($limit, 0) }}</span>
                    </div>
                </div>
            </div>
            <div class="progress-bar-wrap">
                <div class="progress-bar-fill" style="width: {{ $pct }}%; background: {{ $barColor }};"></div>
            </div>
            <div style="font-size: 11px; color: #9ca3af; margin-top: 4px; text-align: right;">{{ number_format($pct, 0) }}% used</div>
        </div>
    @endforeach
</div>

</x-app-layout>
