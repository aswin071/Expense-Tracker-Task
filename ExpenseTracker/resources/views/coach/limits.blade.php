<x-app-layout>

<a href="{{ route('coach.index') }}" class="back-link">&#8592; Budget Coach</a>

<div class="section-heading">Edit Budget Limits</div>

<p style="font-size: 13px; color: #9ca3af; margin-bottom: 16px; margin-top: -8px;">
    Set monthly spending limits per category.
</p>

@if ($errors->any())
    <div style="background: #fee2e2; border-left: 4px solid #dc2626; color: #b91c1c; padding: 12px 14px; border-radius: 8px; margin-bottom: 14px; font-size: 13px;">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

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

<div class="card" style="padding: 0; margin-bottom: 12px;">
    <form method="POST" action="{{ route('coach.limits.update') }}">
        @csrf

        @foreach ($limits as $category => $amount)
            @php $c = $catColors[$category] ?? $catColors['other']; @endphp
            <div style="padding: 14px 16px; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; gap: 12px;">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: {{ $c['bg'] }}; color: {{ $c['text'] }}; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800; flex-shrink: 0;">
                    {{ strtoupper(substr($category, 0, 2)) }}
                </div>
                <div style="flex: 1;">
                    <label for="limit_{{ $category }}" style="font-size: 13px; font-weight: 600; color: #374151; display: block; margin-bottom: 4px;">{{ ucfirst($category) }}</label>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span style="font-size: 14px; color: #9ca3af;">&#8377;</span>
                        <input type="number"
                               name="limits[{{ $category }}]"
                               id="limit_{{ $category }}"
                               value="{{ old('limits.' . $category, $amount) }}"
                               min="100" max="999999" required
                               style="flex: 1; border: 1.5px solid #e5e7eb; border-radius: 8px; padding: 9px 12px; font-size: 15px; font-weight: 600; color: #1a1a2e; outline: none; font-family: inherit;">
                    </div>
                    @error('limits.' . $category)
                        <p style="color: #dc2626; font-size: 12px; margin-top: 3px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        @endforeach

        <div style="padding: 16px;">
            <button type="submit" class="btn-primary">Save Limits</button>
        </div>
    </form>
</div>

<form method="POST" action="{{ route('coach.limits.reset') }}"
      onsubmit="return confirm('Reset all limits back to defaults?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn-secondary" style="font-size: 14px;">
        Reset to defaults
    </button>
</form>

</x-app-layout>
