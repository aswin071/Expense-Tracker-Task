<x-app-layout>

<a href="{{ route('expenses.index') }}" class="back-link">&#8592; Expenses</a>

<div class="section-heading">Add Expense</div>

<div style="background: #ede9fe; border-radius: 10px; padding: 12px 14px; margin-bottom: 18px;">
    <p style="font-size: 11px; font-weight: 700; color: #5b21b6; text-transform: uppercase; letter-spacing: 0.4px;">Spent This Month</p>
    <p style="font-size: 20px; font-weight: 800; color: #4f46e5; margin-top: 2px;">&#8377;{{ number_format($spent, 0) }}</p>
</div>

@if ($errors->any())
    <div style="background: #fee2e2; border-left: 4px solid #dc2626; color: #b91c1c; padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; font-size: 13px;">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="card">
    <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-label" for="amount">Amount (&#8377;)</label>
            <input type="number" name="amount" id="amount" step="0.01" min="0.01"
                   value="{{ old('amount') }}" placeholder="0.00" class="form-input">
            @error('amount') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="category">Category</label>
            <select name="category" id="category" class="form-select">
                <option value="">Select category</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" @selected(old('category') === $cat)>{{ ucfirst($cat) }}</option>
                @endforeach
            </select>
            @error('category') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Description</label>
            <textarea name="description" id="description" rows="3"
                      placeholder="What did you spend on?" class="form-textarea">{{ old('description') }}</textarea>
            @error('description') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="date">Date</label>
            <input type="date" name="date" id="date"
                   value="{{ old('date', now()->toDateString()) }}"
                   max="{{ now()->toDateString() }}" class="form-input">
            @error('date') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="receipt_image">Receipt (optional — JPG, PNG or PDF, max 2 MB)</label>
            <input type="file" name="receipt_image" id="receipt_image" accept=".jpg,.jpeg,.png,.pdf"
                   style="width: 100%; border: 1.5px solid #e5e7eb; border-radius: 10px; padding: 10px 12px; font-size: 13px; background: #f9fafb;">
            @error('receipt_image') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="btn-primary">Save Expense</button>
    </form>
</div>

</x-app-layout>
