<x-app-layout>

    <div class="px-4 pt-4 pb-6 max-w-lg mx-auto space-y-4">

        {{-- Page header --}}
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-bold text-gray-900">Expenses</h1>
        </div>

        {{-- Collapsible filter --}}
        <div x-data="{ open: {{ request()->hasAny(['month','year','category']) ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between bg-white rounded-2xl border border-gray-200 shadow-sm px-4 py-3 text-sm font-medium text-gray-700 min-h-[48px]">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    Filter
                    @if (request()->hasAny(['month','year','category']))
                        <span class="bg-indigo-600 text-white text-xs rounded-full px-1.5 py-0.5">On</span>
                    @endif
                </span>
                <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open" x-cloak class="mt-2 bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
                <form method="GET" action="{{ route('expenses.index') }}" class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Month</label>
                            <select name="month"
                                    class="w-full border border-gray-300 rounded-xl px-3 py-3 text-base focus:ring-2 focus:ring-indigo-500 bg-white">
                                <option value="">All months</option>
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" @selected(request('month') == $m)>
                                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Year</label>
                            <select name="year"
                                    class="w-full border border-gray-300 rounded-xl px-3 py-3 text-base focus:ring-2 focus:ring-indigo-500 bg-white">
                                <option value="">All years</option>
                                @foreach(range(now()->year - 2, now()->year) as $y)
                                    <option value="{{ $y }}" @selected(request('year') == $y)>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Category</label>
                        <select name="category"
                                class="w-full border border-gray-300 rounded-xl px-3 py-3 text-base focus:ring-2 focus:ring-indigo-500 bg-white">
                            <option value="">All categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ ucfirst($cat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit"
                                class="flex-1 bg-indigo-600 text-white font-semibold py-3 rounded-xl text-sm">
                            Apply Filter
                        </button>
                        <a href="{{ route('expenses.index') }}"
                           class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3 rounded-xl text-sm text-center">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Expense cards --}}
        @if ($expenses->isEmpty())
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-10 text-center">
                <div class="text-6xl mb-4">🧾</div>
                <p class="text-gray-700 font-semibold text-lg">No expenses found</p>
                <p class="text-gray-400 text-sm mt-2">
                    @if (request()->hasAny(['month','year','category']))
                        Try clearing your filters, or
                    @endif
                    <a href="{{ route('expenses.create') }}" class="text-indigo-600 font-medium">add a new expense</a>.
                </p>
            </div>
        @else
            @php
                $catIcons = ['food'=>'🍔','transportation'=>'🚗','entertainment'=>'🎬','health'=>'💊','shopping'=>'🛍️','utilities'=>'💡','other'=>'📦'];
                $catColors = ['food'=>'bg-orange-50','transportation'=>'bg-blue-50','entertainment'=>'bg-purple-50','health'=>'bg-red-50','shopping'=>'bg-pink-50','utilities'=>'bg-yellow-50','other'=>'bg-gray-50'];
            @endphp
            <div class="space-y-2">
                @foreach ($expenses as $expense)
                    <div class="flex items-center gap-3 bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-3 group">

                        {{-- Category icon --}}
                        <div class="w-11 h-11 rounded-xl {{ $catColors[$expense->category] ?? 'bg-gray-50' }} flex items-center justify-center text-xl flex-shrink-0">
                            {{ $catIcons[$expense->category] ?? '📦' }}
                        </div>

                        {{-- Main content --}}
                        <a href="{{ route('expenses.show', $expense) }}" class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $expense->description }}</p>
                            <p class="text-xs text-gray-400 mt-0.5 capitalize">
                                {{ $expense->category }} · {{ $expense->date->format('d M Y') }}
                            </p>
                        </a>

                        {{-- Amount --}}
                        <div class="text-right flex-shrink-0">
                            <p class="font-bold text-gray-900">₹{{ number_format($expense->amount, 0) }}</p>
                            <p class="text-xs text-gray-400">{{ number_format($expense->amount, 2) }}</p>
                        </div>

                        {{-- Delete button --}}
                        <form action="{{ route('expenses.destroy', $expense) }}" method="POST"
                              onsubmit="return confirm('Delete this expense?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-11 h-11 flex items-center justify-center rounded-xl bg-red-50 text-red-500 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>

                    </div>
                @endforeach
            </div>

            {{-- Load more / pagination --}}
            @if ($expenses->hasMorePages())
                <div class="text-center pt-2">
                    <a href="{{ $expenses->nextPageUrl() }}"
                       class="inline-block w-full bg-white border border-gray-200 text-gray-700 font-semibold py-3 rounded-2xl text-sm shadow-sm">
                        Load More
                    </a>
                </div>
            @endif

            <p class="text-center text-xs text-gray-400">
                Showing {{ $expenses->firstItem() }}–{{ $expenses->lastItem() }} of {{ $expenses->total() }} expenses
            </p>
        @endif

    </div>

</x-app-layout>
