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
$c = $catColors[$expense->category] ?? $catColors['other'];
@endphp

<a href="{{ route('expenses.index') }}" class="back-link">&#8592; Expenses</a>

{{-- Amount hero --}}
<div style="background: #fff; border-radius: 18px; padding: 24px 20px; margin-bottom: 16px; text-align: center; box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
    <div style="width: 56px; height: 56px; border-radius: 16px; background: {{ $c['bg'] }}; color: {{ $c['text'] }}; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800; margin: 0 auto 12px;">
        {{ strtoupper(substr($expense->category, 0, 2)) }}
    </div>
    <p style="font-size: 32px; font-weight: 800; color: #1a1a2e; letter-spacing: -1px;">&#8377;{{ number_format($expense->amount, 2) }}</p>
    <span class="badge badge-{{ $expense->category }}" style="margin-top: 6px; font-size: 12px; padding: 4px 12px; display: inline-block;">{{ ucfirst($expense->category) }}</span>
</div>

<div class="card" style="padding: 0;">
    <div style="padding: 14px 16px; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
        <span style="font-size: 13px; color: #9ca3af; font-weight: 500; flex-shrink: 0;">Description</span>
        <span style="font-size: 14px; color: #1a1a2e; font-weight: 600; text-align: right;">{{ $expense->description }}</span>
    </div>
    <div style="padding: 14px 16px; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center;">
        <span style="font-size: 13px; color: #9ca3af; font-weight: 500;">Date</span>
        <span style="font-size: 14px; color: #1a1a2e; font-weight: 600;">{{ $expense->date->format('d M Y') }}</span>
    </div>
    <div style="padding: 14px 16px; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center;">
        <span style="font-size: 13px; color: #9ca3af; font-weight: 500;">Added</span>
        <span style="font-size: 14px; color: #1a1a2e; font-weight: 600;">{{ $expense->created_at->format('d M Y, h:i A') }}</span>
    </div>
    <div style="padding: 14px 16px; display: flex; justify-content: space-between; align-items: center;">
        <span style="font-size: 13px; color: #9ca3af; font-weight: 500;">Receipt</span>
        @if ($expense->receipt_image)
            <a href="{{ Storage::url($expense->receipt_image) }}" target="_blank"
               style="background: #ede9fe; color: #4f46e5; padding: 7px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none;">
                View Receipt
            </a>
        @else
            <span style="font-size: 13px; color: #9ca3af;">No receipt</span>
        @endif
    </div>
</div>

<div style="display: flex; gap: 10px; margin-top: 4px;">
    <a href="{{ route('expenses.edit', $expense) }}" class="btn-secondary" style="flex: 1; text-align: center; padding: 13px;">Edit</a>
    <form action="{{ route('expenses.destroy', $expense) }}" method="POST" style="flex: 1;"
          onsubmit="return confirm('Delete this expense?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-danger" style="width: 100%; padding: 13px; border-radius: 12px; font-size: 15px;">Delete</button>
    </form>
</div>

</x-app-layout>
