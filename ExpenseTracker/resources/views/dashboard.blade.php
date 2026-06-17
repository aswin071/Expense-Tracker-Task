<x-app-layout>

    <div class="px-4 pt-4 pb-6 space-y-4 max-w-lg mx-auto">

        {{-- Auto-log banner --}}
        @if ($autoLogged->isNotEmpty())
            <div class="bg-green-500 text-white px-4 py-3 rounded-2xl flex items-center gap-3">
                <span class="text-xl flex-shrink-0">🔄</span>
                <p class="text-sm font-medium">
                    <strong>{{ $autoLogged->count() }}</strong>
                    recurring expense{{ $autoLogged->count() > 1 ? 's' : '' }} auto-logged today.
                    <a href="{{ route('expenses.index') }}" class="underline ml-1">View</a>
                </p>
            </div>
        @endif

        {{-- Greeting + monthly total card --}}
        <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 text-white rounded-3xl p-5">
            @php
                $hour     = now()->hour;
                $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
            @endphp
            <p class="text-indigo-200 text-sm font-medium">{{ $greeting }},</p>
            <p class="text-xl font-bold mt-0.5">{{ auth()->user()->name }} 👋</p>

            <div class="mt-5">
                <p class="text-indigo-300 text-xs uppercase tracking-wider">Spent this month</p>
                <p class="text-4xl font-bold mt-1">₹{{ number_format($monthlyTotal, 0) }}</p>
                <p class="text-indigo-300 text-xs mt-1">{{ now()->format('F Y') }}</p>
            </div>

            <a href="{{ route('reports.monthly') }}"
               class="mt-4 inline-flex items-center gap-1 text-indigo-200 text-sm">
                View full report
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        {{-- Category spending pills (horizontal scroll) --}}
        <div>
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">This month by category</h3>
            <div class="flex gap-2 overflow-x-auto pb-2 -mx-4 px-4">
                @php
                    $catIcons = ['food'=>'🍔','transportation'=>'🚗','entertainment'=>'🎬','health'=>'💊','shopping'=>'🛍️','utilities'=>'💡','other'=>'📦'];
                @endphp
                @foreach ($categoryTotals as $cat => $total)
                    @if ($total > 0)
                        <a href="{{ route('expenses.index', ['category' => $cat]) }}"
                           class="flex-shrink-0 bg-white rounded-2xl px-4 py-3 shadow-sm border border-gray-100 text-center min-w-[90px]">
                            <div class="text-xl">{{ $catIcons[$cat] ?? '📦' }}</div>
                            <div class="text-xs text-gray-500 mt-1 capitalize">{{ $cat }}</div>
                            <div class="text-sm font-bold text-gray-800 mt-0.5">₹{{ number_format($total, 0) }}</div>
                        </a>
                    @endif
                @endforeach
                @if (collect($categoryTotals)->sum() === 0.0)
                    <p class="text-sm text-gray-400 py-3">No spending this month yet.</p>
                @endif
            </div>
        </div>

        {{-- Recurring schedule --}}
        @if ($recurringExpenses->isNotEmpty())
            <div>
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Monthly schedule</h3>
                    <a href="{{ route('recurring.index') }}" class="text-indigo-600 text-sm font-medium">Manage</a>
                </div>
                <div class="flex gap-2 overflow-x-auto pb-1 -mx-4 px-4">
                    @foreach ($recurringExpenses as $rec)
                        <div class="flex-shrink-0 bg-white rounded-2xl border border-gray-100 shadow-sm px-3 py-3 min-w-[120px]">
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="text-base">{{ $catIcons[$rec->category] ?? '📦' }}</span>
                                <span class="bg-indigo-100 text-indigo-700 text-[10px] font-bold px-1.5 py-0.5 rounded-full">🔁 AUTO</span>
                            </div>
                            <p class="text-xs font-semibold text-gray-800 truncate">{{ $rec->description }}</p>
                            <p class="text-sm font-bold text-gray-900 mt-1">₹{{ number_format($rec->amount, 0) }}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">Every {{ $rec->day_of_month }}{{ in_array($rec->day_of_month, [1,21]) ? 'st' : (in_array($rec->day_of_month, [2,22]) ? 'nd' : (in_array($rec->day_of_month, [3,23]) ? 'rd' : 'th')) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Recent expenses --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Recent expenses</h3>
                <a href="{{ route('expenses.index') }}" class="text-indigo-600 text-sm font-medium">See all</a>
            </div>

            @if ($recentExpenses->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center">
                    <div class="text-5xl mb-3">🧾</div>
                    <p class="text-gray-500 font-medium">No expenses yet</p>
                    <p class="text-gray-400 text-sm mt-1">Tap the + button to log your first one.</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach ($recentExpenses as $expense)
                        <a href="{{ route('expenses.show', $expense) }}"
                           class="flex items-center gap-3 bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-lg flex-shrink-0">
                                {{ $catIcons[$expense->category] ?? '📦' }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $expense->description }}</p>
                                    @if (str_ends_with($expense->description, '(Auto)'))
                                        <span class="flex-shrink-0 bg-indigo-100 text-indigo-600 text-[10px] font-bold px-1.5 py-0.5 rounded-full">🔁</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400 mt-0.5 capitalize">{{ $expense->category }} · {{ $expense->date->format('d M') }}</p>
                            </div>
                            <div class="text-sm font-bold text-gray-900 flex-shrink-0">
                                ₹{{ number_format($expense->amount, 0) }}
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Quick links --}}
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('recurring.index') }}"
               class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
                <span class="text-2xl">🔁</span>
                <span class="text-sm font-semibold text-gray-700">Recurring</span>
            </a>
            <a href="{{ route('profile.edit') }}"
               class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
                <span class="text-2xl">⚙️</span>
                <span class="text-sm font-semibold text-gray-700">Profile</span>
            </a>
        </div>

    </div>

</x-app-layout>
