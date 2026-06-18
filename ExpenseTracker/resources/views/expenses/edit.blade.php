<x-app-layout>

<a href="{{ route('expenses.show', $expense) }}" class="back-link">&#8592; Expense Detail</a>

<div class="section-heading">Edit Expense</div>

@if ($errors->any())
    <div style="background: #fee2e2; border-left: 4px solid #dc2626; color: #b91c1c; padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; font-size: 13px;">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="card">
    <form action="{{ route('expenses.update', $expense) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label" for="amount">Amount (&#8377;)</label>
            <input type="number" name="amount" id="amount" step="0.01" min="0.01"
                   value="{{ old('amount', $expense->amount) }}" class="form-input">
            @error('amount') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="category">Category</label>
            <select name="category" id="category" class="form-select">
                <option value="">Select category</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" @selected(old('category', $expense->category) === $cat)>{{ ucfirst($cat) }}</option>
                @endforeach
            </select>
            @error('category') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Description</label>
            <textarea name="description" id="description" rows="3" class="form-textarea">{{ old('description', $expense->description) }}</textarea>
            @error('description') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="date">Date</label>
            <input type="date" name="date" id="date"
                   value="{{ old('date', $expense->date->toDateString()) }}"
                   max="{{ now()->toDateString() }}" class="form-input">
            @error('date') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Receipt</label>
            @if ($expense->receipt_image)
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 10px 14px; margin-bottom: 10px;">
                    <a href="{{ Storage::url($expense->receipt_image) }}" target="_blank"
                       style="color: #16a34a; font-size: 13px; font-weight: 600; text-decoration: none;">View current receipt</a>
                    <span style="font-size: 12px; color: #9ca3af; margin-left: 6px;">(upload a new file to replace)</span>
                </div>
            @endif
            <input type="file" name="receipt_image" id="receipt_image" accept=".jpg,.jpeg,.png,.pdf"
                   style="width: 100%; border: 1.5px solid #e5e7eb; border-radius: 10px; padding: 10px 12px; font-size: 13px; background: #f9fafb;">
            @error('receipt_image') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="btn-primary">Update Expense</button>
    </form>
</div>

</x-app-layout>
