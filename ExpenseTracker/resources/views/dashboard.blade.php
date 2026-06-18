<x-app-layout>

@php
$catColors = [
    'food'           => ['bg' => '#fef3c7', 'text' => '#92400e'],
    'transportation' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
    'entertainment'  => ['bg' => '#ede9fe', 'text' => '#5b21b6'],
    'health'         => ['bg' => '#dcfce7', 'text' => '#166534'],
    'shopping'       => ['bg' => '#fce7f3', 'text' => '#9d174d'],
    'utilities'      => ['bg' => '#e0f2fe', 'text' => '#0c4a6e'],
    'other'          => ['bg' => '#f3f4f6', 'text' => '#374151'],
];
@endphp

{{-- Hero spend card --}}
<div style="background: linear-gradient(135deg, #4f46e5, #7c3aed); border-radius: 18px; padding: 22px 20px 20px; margin-bottom: 16px; color: #fff;">
    <p style="font-size: 12px; opacity: 0.8; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase;">
        {{ now()->format('F Y') }}
    </p>
    <p style="font-size: 36px; font-weight: 800; margin: 4px 0 2px; letter-spacing: -1px;">
        &#8377;{{ number_format($monthlyTotal, 0) }}
    </p>
    <p style="font-size: 13px; opacity: 0.75;">Total spent this month</p>
    <div style="display: flex; gap: 10px; margin-top: 16px;">
        <a href="{{ route('expenses.create') }}"
           style="flex: 1; background: rgba(255,255,255,0.2); border-radius: 10px; padding: 10px; text-align: center; color: #fff; text-decoration: none; font-size: 13px; font-weight: 600;">
            + Add Expense
        </a>
        <a href="{{ route('reports.monthly') }}"
           style="flex: 1; background: rgba(255,255,255,0.2); border-radius: 10px; padding: 10px; text-align: center; color: #fff; text-decoration: none; font-size: 13px; font-weight: 600;">
            View Report
        </a>
    </div>
</div>

{{-- Recent expenses --}}
<div class="section-heading">Recent Expenses</div>

@if ($recentExpenses->isEmpty())
    <div class="card" style="text-align: center; color: #9ca3af; padding: 32px 16px;">
        <p style="font-size: 14px; margin-bottom: 14px;">No expenses yet. Add your first one!</p>
        <a href="{{ route('expenses.create') }}" class="btn-primary" style="display: inline-block; width: auto; padding: 10px 24px; font-size: 14px;">Add Expense</a>
    </div>
@else
    <div class="card" style="padding: 4px 16px;">
        @foreach ($recentExpenses as $expense)
            @php $c = $catColors[$expense->category] ?? $catColors['other']; @endphp
            <a href="{{ route('expenses.show', $expense) }}" class="expense-item">
                <div class="expense-item-left">
                    <div class="expense-cat-icon" style="background: {{ $c['bg'] }}; color: {{ $c['text'] }}; font-size: 12px; font-weight: 800; letter-spacing: 0;">
                        {{ strtoupper(substr($expense->category, 0, 2)) }}
                    </div>
                    <div>
                        <div class="expense-item-desc">{{ $expense->description }}</div>
                        <div class="expense-item-meta">{{ ucfirst($expense->category) }} &middot; {{ $expense->date->format('d M') }}</div>
                    </div>
                </div>
                <span class="expense-item-amount">&#8377;{{ number_format($expense->amount, 0) }}</span>
            </a>
        @endforeach
    </div>
    <a href="{{ route('expenses.index') }}" style="display: block; text-align: center; color: #4f46e5; font-size: 14px; font-weight: 600; text-decoration: none; margin-bottom: 16px;">
        View all expenses &rarr;
    </a>
@endif

{{-- Recurring schedule --}}
<div class="section-heading">Recurring Schedule</div>

@if ($recurring->isEmpty())
    <div class="card" style="text-align: center; color: #9ca3af; padding: 24px 16px;">
        <p style="font-size: 14px;">No recurring expenses set up.</p>
        <a href="{{ route('recurring.index') }}" class="btn-ghost" style="display: inline-block; margin-top: 10px; font-size: 13px;">Set up recurring</a>
    </div>
@else
    @php $today = now()->day; @endphp
    <div class="card" style="padding: 4px 0;">
        @foreach ($recurring as $item)
            @php
                $paidThisMonth = $item->last_logged_at &&
                    $item->last_logged_at->month === now()->month &&
                    $item->last_logged_at->year === now()->year;

                if ($paidThisMonth) {
                    $statusLabel = 'Paid';
                    $statusClass = 'badge-paid';
                } elseif ($item->is_active && $item->day_of_month < $today) {
                    $statusLabel = 'Overdue';
                    $statusClass = 'badge-overdue';
                } elseif ($item->day_of_month == $today) {
                    $statusLabel = 'Due Today';
                    $statusClass = 'badge-overdue';
                } else {
                    $statusLabel = 'Day ' . $item->day_of_month;
                    $statusClass = 'badge-upcoming';
                }
            @endphp
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-bottom: 1px solid #f3f4f6;">
                <div>
                    <div style="font-size: 14px; font-weight: 600; color: #1a1a2e;">{{ $item->description }}</div>
                    <div style="font-size: 12px; color: #9ca3af; margin-top: 2px;">{{ ucfirst($item->category) }}</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 14px; font-weight: 700; color: #1a1a2e;">&#8377;{{ number_format($item->amount, 0) }}</div>
                    <span class="badge {{ $statusClass }}" style="margin-top: 4px; display: inline-block;">{{ $statusLabel }}</span>
                </div>
            </div>
        @endforeach
    </div>
    <a href="{{ route('recurring.index') }}" style="display: block; text-align: center; color: #4f46e5; font-size: 14px; font-weight: 600; text-decoration: none; margin-bottom: 16px;">
        Manage recurring &rarr;
    </a>
@endif

</x-app-layout>
