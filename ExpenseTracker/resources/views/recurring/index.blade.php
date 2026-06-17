<x-app-layout>

    <div x-data="{ sheet: false, submitting: false, selectedCategory: '{{ old('category', '') }}' }"
         class="px-4 pt-4 pb-6 max-w-lg mx-auto space-y-4">

        {{-- Page header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Recurring</h1>
                <p class="text-sm text-gray-400">{{ now()->format('F Y') }}</p>
            </div>
            <button @click="sheet = true"
                    class="flex items-center gap-2 bg-indigo-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl min-h-[44px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Add New
            </button>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4">
                <p class="text-sm font-semibold text-red-700 mb-1">Please fix the following:</p>
                <ul class="text-sm text-red-600 space-y-0.5">
                    @foreach ($errors->all() as $error)<li>• {{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        @php
            $catIcons  = ['food'=>'🍔','transportation'=>'🚗','entertainment'=>'🎬','health'=>'💊','shopping'=>'🛍️','utilities'=>'💡','other'=>'📦'];
            $catColors = ['food'=>'bg-orange-50','transportation'=>'bg-blue-50','entertainment'=>'bg-purple-50','health'=>'bg-red-50','shopping'=>'bg-pink-50','utilities'=>'bg-yellow-50','other'=>'bg-gray-50'];
            $today     = now()->day;
        @endphp

        {{-- Summary strip --}}
        @if ($recurring->isNotEmpty())
            @php
                $paidCount     = $recurring->filter(fn($r) => $r->last_logged_at && $r->last_logged_at->month === now()->month && $r->last_logged_at->year === now()->year)->count();
                $overdueCount  = $recurring->filter(fn($r) => $r->is_active && $r->day_of_month < $today && (!$r->last_logged_at || $r->last_logged_at->month !== now()->month || $r->last_logged_at->year !== now()->year))->count();
                $totalMonthly  = $recurring->where('is_active', true)->sum('amount');
            @endphp
            <div class="grid grid-cols-3 gap-2">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-3 py-3 text-center">
                    <p class="text-xs text-gray-400">Monthly total</p>
                    <p class="text-base font-bold text-gray-900 mt-1">₹{{ number_format($totalMonthly, 0) }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-3 py-3 text-center">
                    <p class="text-xs text-gray-400">Paid this month</p>
                    <p class="text-base font-bold text-green-600 mt-1">{{ $paidCount }} / {{ $recurring->count() }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-3 py-3 text-center">
                    <p class="text-xs text-gray-400">Overdue</p>
                    <p class="text-base font-bold {{ $overdueCount > 0 ? 'text-red-500' : 'text-gray-400' }} mt-1">{{ $overdueCount }}</p>
                </div>
            </div>
        @endif

        {{-- Recurring expense cards --}}
        @if ($recurring->isEmpty())
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-10 text-center">
                <div class="text-6xl mb-4">🔁</div>
                <p class="text-gray-700 font-semibold text-lg">No recurring expenses</p>
                <p class="text-gray-400 text-sm mt-2">Tap <strong>Add New</strong> to set one up.<br>They auto-log on your chosen day each month.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($recurring as $item)
                    @php
                        // Determine payment status for this month
                        $paidThisMonth = $item->last_logged_at &&
                                         $item->last_logged_at->month === now()->month &&
                                         $item->last_logged_at->year  === now()->year;

                        if ($paidThisMonth) {
                            $status      = 'paid';
                            $statusLabel = 'Paid';
                            $statusBg    = 'bg-green-100 text-green-700';
                            $cardBorder  = 'border-green-100';
                            $leftBorder  = 'border-l-4 border-l-green-400';
                        } elseif (!$item->is_active) {
                            $status      = 'paused';
                            $statusLabel = 'Paused';
                            $statusBg    = 'bg-gray-100 text-gray-500';
                            $cardBorder  = 'border-gray-100';
                            $leftBorder  = '';
                        } elseif ($item->day_of_month === $today) {
                            $status      = 'due_today';
                            $statusLabel = 'Due Today';
                            $statusBg    = 'bg-blue-100 text-blue-700';
                            $cardBorder  = 'border-blue-100';
                            $leftBorder  = 'border-l-4 border-l-blue-500';
                        } elseif ($item->day_of_month < $today) {
                            $status      = 'overdue';
                            $statusLabel = 'Overdue';
                            $statusBg    = 'bg-red-100 text-red-700';
                            $cardBorder  = 'border-red-100';
                            $leftBorder  = 'border-l-4 border-l-red-500';
                        } else {
                            $status      = 'upcoming';
                            $statusLabel = 'Upcoming';
                            $statusBg    = 'bg-indigo-50 text-indigo-600';
                            $cardBorder  = 'border-gray-100';
                            $leftBorder  = 'border-l-4 border-l-indigo-300';
                        }

                        // Ordinal suffix
                        $d = $item->day_of_month;
                        $suffix = match(true) {
                            in_array($d, [1,21]) => 'st',
                            in_array($d, [2,22]) => 'nd',
                            in_array($d, [3,23]) => 'rd',
                            default              => 'th',
                        };
                    @endphp

                    <div class="bg-white rounded-2xl border {{ $cardBorder }} {{ $leftBorder }} shadow-sm overflow-hidden
                                {{ !$item->is_active ? 'opacity-60' : '' }}">

                        <div class="p-4">
                            <div class="flex items-start gap-3">
                                {{-- Icon --}}
                                <div class="w-11 h-11 rounded-xl {{ $catColors[$item->category] ?? 'bg-gray-50' }} flex items-center justify-center text-xl flex-shrink-0">
                                    {{ $catIcons[$item->category] ?? '📦' }}
                                </div>

                                {{-- Details --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <p class="font-semibold text-gray-900 truncate">{{ $item->description }}</p>
                                        <span class="text-[11px] font-bold px-2 py-0.5 rounded-full {{ $statusBg }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-400 mt-0.5 capitalize">
                                        {{ $item->category }} · {{ $d }}{{ $suffix }} each month
                                    </p>
                                    @if ($paidThisMonth)
                                        <p class="text-xs text-green-600 font-medium mt-1">
                                            ✓ Logged {{ $item->last_logged_at->format('d M') }}
                                        </p>
                                    @elseif ($status === 'overdue')
                                        <p class="text-xs text-red-500 font-medium mt-1">
                                            {{ ($today - $item->day_of_month) }} day{{ ($today - $item->day_of_month) > 1 ? 's' : '' }} overdue
                                        </p>
                                    @elseif ($status === 'upcoming')
                                        <p class="text-xs text-gray-400 mt-1">
                                            Due in {{ $item->day_of_month - $today }} day{{ ($item->day_of_month - $today) > 1 ? 's' : '' }}
                                        </p>
                                    @elseif ($status === 'due_today')
                                        <p class="text-xs text-blue-500 font-medium mt-1">Due today!</p>
                                    @endif
                                </div>

                                {{-- Amount + toggle --}}
                                <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                    <p class="font-bold text-gray-900">₹{{ number_format($item->amount, 0) }}</p>
                                    <form method="POST" action="{{ route('recurring.toggle', $item) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="relative inline-flex items-center h-7 w-12 rounded-full transition-colors
                                                       {{ $item->is_active ? 'bg-indigo-600' : 'bg-gray-300' }}"
                                                title="{{ $item->is_active ? 'Pause' : 'Activate' }}">
                                            <span class="inline-block w-5 h-5 bg-white rounded-full shadow transform transition-transform
                                                         {{ $item->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                        </button>
                                    </form>
                                    <span class="text-xs font-medium {{ $item->is_active ? 'text-green-600' : 'text-gray-400' }}">
                                        {{ $item->is_active ? 'Active' : 'Paused' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Action bar --}}
                        <div class="px-4 pb-3 flex items-center justify-between gap-2">

                            {{-- Mark as Paid button (overdue or due today, not yet paid) --}}
                            @if (in_array($status, ['overdue', 'due_today']) && $item->is_active)
                                <form method="POST" action="{{ route('recurring.markPaid', $item) }}" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center justify-center gap-2 bg-green-500 text-white text-sm font-bold
                                                   px-4 py-2.5 rounded-xl min-h-[44px] active:bg-green-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Mark as Paid · ₹{{ number_format($item->amount, 0) }}
                                    </button>
                                </form>
                            @elseif ($paidThisMonth)
                                <div class="flex-1 flex items-center gap-2 bg-green-50 border border-green-100 text-green-700
                                            text-sm font-semibold px-4 py-2.5 rounded-xl">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Added to expenses
                                    <span class="ml-auto text-green-500 font-medium text-xs">{{ $item->last_logged_at->format('d M') }}</span>
                                </div>
                            @else
                                <div class="flex-1 flex items-center gap-2 text-gray-400 text-sm px-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    @if ($status === 'upcoming')
                                        Scheduled {{ $d }}{{ $suffix }} {{ now()->format('M') }}
                                    @else
                                        {{ ucfirst($status) }}
                                    @endif
                                </div>
                            @endif

                            {{-- Delete --}}
                            <form method="POST" action="{{ route('recurring.destroy', $item) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($item->description) }}? Already-logged expenses are kept.')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-10 h-10 flex items-center justify-center rounded-xl text-red-400 bg-red-50 flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <p class="text-xs text-gray-400 text-center pb-2">
                Expenses auto-log at midnight · "Mark as Paid" adds manually.
            </p>
        @endif

        {{-- ─── Bottom sheet: Add new recurring ─── --}}
        <div x-show="sheet" x-cloak @click="sheet = false"
             class="fixed inset-0 bg-black/40 z-40 backdrop-blur-sm"></div>

        <div x-show="sheet" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             class="fixed bottom-0 inset-x-0 z-50 bg-white rounded-t-3xl shadow-2xl max-h-[90vh] overflow-y-auto">

            <div class="flex justify-center pt-3 pb-1">
                <div class="w-10 h-1 bg-gray-300 rounded-full"></div>
            </div>

            <div class="px-4 pb-2 pt-1 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Add Recurring Expense</h2>
                <button @click="sheet = false"
                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('recurring.store') }}"
                  class="px-4 pb-8 space-y-4"
                  @submit="submitting = true"
                  autocomplete="off">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Amount <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold text-lg">₹</span>
                        <input type="number" name="amount" value="{{ old('amount') }}"
                               step="0.01" min="0.01" inputmode="decimal" placeholder="0.00"
                               class="w-full border rounded-2xl pl-9 pr-4 py-4 text-lg font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500
                                      @error('amount') border-red-400 bg-red-50 @else border-gray-300 bg-white @enderror">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description <span class="text-red-500">*</span></label>
                    <input type="text" name="description" value="{{ old('description') }}" maxlength="500"
                           placeholder="e.g. Netflix, Gym membership"
                           class="w-full border rounded-2xl px-4 py-4 text-base focus:outline-none focus:ring-2 focus:ring-indigo-500
                                  @error('description') border-red-400 bg-red-50 @else border-gray-300 @enderror">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Category <span class="text-red-500">*</span></label>
                    <input type="hidden" name="category" :value="selectedCategory">
                    <div class="grid grid-cols-4 gap-2">
                        @php
                            $catData = ['food'=>['icon'=>'🍔','label'=>'Food','bg'=>'bg-orange-100'],'transportation'=>['icon'=>'🚗','label'=>'Transport','bg'=>'bg-blue-100'],'entertainment'=>['icon'=>'🎬','label'=>'Fun','bg'=>'bg-purple-100'],'health'=>['icon'=>'💊','label'=>'Health','bg'=>'bg-red-100'],'shopping'=>['icon'=>'🛍️','label'=>'Shopping','bg'=>'bg-pink-100'],'utilities'=>['icon'=>'💡','label'=>'Bills','bg'=>'bg-yellow-100'],'other'=>['icon'=>'📦','label'=>'Other','bg'=>'bg-gray-100']];
                        @endphp
                        @foreach ($catData as $val => $meta)
                            <button type="button" @click="selectedCategory = '{{ $val }}'"
                                    class="relative flex flex-col items-center gap-1 py-2.5 rounded-2xl border-2 transition"
                                    :class="selectedCategory === '{{ $val }}' ? 'border-indigo-500 bg-indigo-50' : 'border-transparent bg-gray-50'">
                                <div class="w-9 h-9 rounded-xl {{ $meta['bg'] }} flex items-center justify-center text-lg">{{ $meta['icon'] }}</div>
                                <span class="text-[11px] font-semibold text-gray-600">{{ $meta['label'] }}</span>
                                <span x-show="selectedCategory === '{{ $val }}'"
                                      class="absolute top-1 right-1 w-4 h-4 bg-indigo-500 rounded-full flex items-center justify-center">
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Day of Month <span class="text-red-500">*</span>
                        <span class="text-gray-400 font-normal">(1–28)</span>
                    </label>
                    <input type="number" name="day_of_month" value="{{ old('day_of_month') }}"
                           min="1" max="28" inputmode="numeric" placeholder="e.g. 1"
                           class="w-full border rounded-2xl px-4 py-4 text-base focus:outline-none focus:ring-2 focus:ring-indigo-500
                                  @error('day_of_month') border-red-400 bg-red-50 @else border-gray-300 bg-white @enderror">
                    @error('day_of_month')
                        <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" :disabled="submitting"
                        class="w-full bg-indigo-600 text-white font-bold py-4 rounded-2xl text-base min-h-[56px]
                               disabled:opacity-60 flex items-center justify-center gap-2">
                    <span x-show="!submitting">Save Recurring Expense</span>
                    <span x-show="submitting" x-cloak class="flex items-center gap-2">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Saving…
                    </span>
                </button>
            </form>
        </div>

    </div>

    @if ($errors->any())
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => {
                    const el = document.querySelector('[x-data]');
                    if (el && el._x_dataStack) { el._x_dataStack[0].sheet = true; }
                }, 100);
            });
        </script>
    @endif

</x-app-layout>
