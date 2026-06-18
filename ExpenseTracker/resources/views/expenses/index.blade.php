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

<div class="page-header">
    <h1 style="font-size: 20px; font-weight: 700;">Expenses</h1>
    <a href="{{ route('expenses.create') }}" class="btn-primary" style="width: auto; padding: 9px 16px; font-size: 14px; display: inline-block;">+ Add</a>
</div>

{{-- Filter bar --}}
<div class="card" style="padding: 14px 16px; margin-bottom: 14px;">
    <form method="GET" action="{{ route('expenses.index') }}">
        <div style="display: flex; gap: 8px; margin-bottom: 10px;">
            <div style="flex: 1;">
                <label style="font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.4px; display: block; margin-bottom: 4px;">Month</label>
                <select name="month" class="form-select" style="padding: 9px 10px; font-size: 13px;">
                    <option value="">All</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" @selected(request('month') == $m)>
                            {{ \Carbon\Carbon::create()->month($m)->format('M') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="flex: 1;">
                <label style="font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.4px; display: block; margin-bottom: 4px;">Year</label>
                <select name="year" class="form-select" style="padding: 9px 10px; font-size: 13px;">
                    <option value="">All</option>
                    @foreach(range(now()->year - 2, now()->year) as $y)
                        <option value="{{ $y }}" @selected(request('year') == $y)>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex: 1;">
                <label style="font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.4px; display: block; margin-bottom: 4px;">Category</label>
                <select name="category" class="form-select" style="padding: 9px 10px; font-size: 13px;">
                    <option value="">All</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn-primary" style="flex: 1; padding: 10px; font-size: 14px;">Apply Filter</button>
            <a href="{{ route('expenses.index') }}" class="btn-secondary" style="flex: 0.4; padding: 10px; font-size: 14px; text-align: center;">Reset</a>
        </div>
    </form>
</div>

@if ($expenses->isEmpty())
    <div class="card" style="text-align: center; color: #9ca3af; padding: 36px 16px;">
        <p style="font-size: 14px;">No expenses found.</p>
    </div>
@else
    <div class="card" style="padding: 4px 16px;">
        @foreach ($expenses as $expense)
            @php $c = $catColors[$expense->category] ?? $catColors['other']; @endphp
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f3f4f6;">
                <a href="{{ route('expenses.show', $expense) }}" style="display: flex; align-items: center; gap: 12px; flex: 1; text-decoration: none; color: inherit; min-width: 0;">
                    <div class="expense-cat-icon" style="background: {{ $c['bg'] }}; color: {{ $c['text'] }}; font-size: 11px; font-weight: 800; flex-shrink: 0;">
                        {{ strtoupper(substr($expense->category, 0, 2)) }}
                    </div>
                    <div style="min-width: 0;">
                        <div style="font-size: 14px; font-weight: 600; color: #1a1a2e; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $expense->description }}</div>
                        <div style="font-size: 12px; color: #9ca3af; margin-top: 2px;">{{ ucfirst($expense->category) }} &middot; {{ $expense->date->format('d M Y') }}</div>
                    </div>
                </a>
                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 6px; flex-shrink: 0; margin-left: 8px;">
                    <span style="font-size: 14px; font-weight: 700; color: #1a1a2e;">&#8377;{{ number_format($expense->amount, 0) }}</span>
                    <form action="{{ route('expenses.destroy', $expense) }}" method="POST"
                          onsubmit="return confirm('Delete this expense?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: none; border: none; color: #dc2626; font-size: 12px; font-weight: 600; cursor: pointer; padding: 0; font-family: inherit;">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 0; font-size: 13px; color: #6b7280;">
        <span>Page {{ $expenses->currentPage() }} of {{ $expenses->lastPage() }}</span>
        <div style="display: flex; gap: 8px;">
            @if ($expenses->previousPageUrl())
                <a href="{{ $expenses->previousPageUrl() }}" class="btn-ghost" style="padding: 7px 14px; font-size: 13px;">&larr; Prev</a>
            @endif
            @if ($expenses->hasMorePages())
                <a href="{{ $expenses->nextPageUrl() }}" class="btn-ghost" style="padding: 7px 14px; font-size: 13px;">Next &rarr;</a>
            @endif
        </div>
    </div>
@endif

</x-app-layout>
