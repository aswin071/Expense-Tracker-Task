<x-app-layout>

<div class="section-heading">Recurring Expenses</div>

{{-- Existing recurring list --}}
@if ($recurring->isNotEmpty())
    @php $today = now()->day; @endphp
    <div class="card" style="padding: 0; margin-bottom: 16px;">
        @foreach ($recurring as $item)
            @php
                $paidThisMonth = $item->last_logged_at &&
                    $item->last_logged_at->month === now()->month &&
                    $item->last_logged_at->year === now()->year;

                if ($paidThisMonth) {
                    $statusLabel = 'Paid ' . $item->last_logged_at->format('d M');
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
            <div style="padding: 14px 16px; border-bottom: 1px solid #f3f4f6;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                    <div>
                        <div style="font-size: 15px; font-weight: 700; color: #1a1a2e;">{{ $item->description }}</div>
                        <div style="font-size: 12px; color: #9ca3af; margin-top: 2px;">{{ ucfirst($item->category) }} &middot; Every month on day {{ $item->day_of_month }}</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 16px; font-weight: 800; color: #1a1a2e;">&#8377;{{ number_format($item->amount, 0) }}</div>
                        <span class="badge {{ $statusClass }}" style="margin-top: 4px;">{{ $statusLabel }}</span>
                    </div>
                </div>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <form method="POST" action="{{ route('recurring.toggle', $item) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-ghost" style="font-size: 12px; padding: 6px 12px;">
                            {{ $item->is_active ? 'Pause' : 'Activate' }}
                        </button>
                    </form>
                    @if (!$paidThisMonth && $item->is_active)
                        <form method="POST" action="{{ route('recurring.markPaid', $item) }}">
                            @csrf
                            <button type="submit" style="background: #dcfce7; color: #15803d; border: none; border-radius: 8px; padding: 6px 12px; font-size: 12px; font-weight: 600; cursor: pointer; font-family: inherit;">
                                Mark as Paid
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('recurring.destroy', $item) }}"
                          onsubmit="return confirm('Delete this recurring expense?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger" style="font-size: 12px; padding: 6px 12px;">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="card" style="text-align: center; color: #9ca3af; padding: 28px 16px; margin-bottom: 16px;">
        <div style="font-size: 36px; margin-bottom: 8px;">&#128197;</div>
        <p style="font-size: 14px;">No recurring expenses yet.</p>
    </div>
@endif

{{-- Add new form --}}
<div class="section-heading">Add New Recurring</div>

@if ($errors->any())
    <div style="background: #fee2e2; border-left: 4px solid #dc2626; color: #b91c1c; padding: 12px 14px; border-radius: 8px; margin-bottom: 14px; font-size: 13px;">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="card">
    <form method="POST" action="{{ route('recurring.store') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="description">Description</label>
            <input type="text" name="description" id="description" value="{{ old('description') }}"
                   maxlength="500" placeholder="e.g. Netflix, Rent, Gym" class="form-input">
            @error('description') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div style="display: flex; gap: 12px;">
            <div class="form-group" style="flex: 1;">
                <label class="form-label" for="amount">Amount (&#8377;)</label>
                <input type="number" name="amount" id="amount" step="0.01" min="0.01"
                       value="{{ old('amount') }}" placeholder="0.00" class="form-input">
                @error('amount') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group" style="flex: 1;">
                <label class="form-label" for="day_of_month">Day (1–28)</label>
                <input type="number" name="day_of_month" id="day_of_month" min="1" max="28"
                       value="{{ old('day_of_month') }}" placeholder="1" class="form-input">
                @error('day_of_month') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="category">Category</label>
            <select name="category" id="category" class="form-select">
                <option value="">Select category</option>
                @foreach(\App\Models\Expense::CATEGORIES as $cat)
                    <option value="{{ $cat }}" @selected(old('category') === $cat)>{{ ucfirst($cat) }}</option>
                @endforeach
            </select>
            @error('category') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="btn-primary">Save Recurring Expense</button>
    </form>
</div>

</x-app-layout>
