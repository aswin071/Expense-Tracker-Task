<x-app-layout>

    <div class="max-w-lg mx-auto px-4 pt-4 pb-6 space-y-3">

        @php
            $catIcons  = ['food'=>'🍔','transportation'=>'🚗','entertainment'=>'🎬','health'=>'💊','shopping'=>'🛍️','utilities'=>'💡','other'=>'📦'];
            $catColors = ['food'=>'bg-orange-100 text-orange-600','transportation'=>'bg-blue-100 text-blue-600','entertainment'=>'bg-purple-100 text-purple-600','health'=>'bg-red-100 text-red-600','shopping'=>'bg-pink-100 text-pink-600','utilities'=>'bg-yellow-100 text-yellow-600','other'=>'bg-gray-100 text-gray-600'];
            $catGrads  = ['food'=>'from-orange-500 to-red-500','transportation'=>'from-blue-500 to-indigo-600','entertainment'=>'from-purple-500 to-pink-600','health'=>'from-red-500 to-rose-600','shopping'=>'from-pink-500 to-fuchsia-600','utilities'=>'from-yellow-400 to-amber-500','other'=>'from-gray-500 to-gray-700'];
        @endphp

        {{-- Header row --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('expenses.index') }}"
               class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <a href="{{ route('expenses.edit', $expense) }}"
               class="w-10 h-10 flex items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </a>
        </div>

        {{-- Hero card --}}
        <div class="bg-gradient-to-br {{ $catGrads[$expense->category] ?? 'from-indigo-600 to-indigo-800' }} text-white rounded-3xl p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-3xl">
                    {{ $catIcons[$expense->category] ?? '📦' }}
                </div>
                <div>
                    <p class="text-white/70 text-xs uppercase tracking-widest font-semibold">{{ $expense->category }}</p>
                    <p class="text-white font-semibold text-base mt-0.5">{{ $expense->date->format('d F Y') }}</p>
                </div>
                @if (str_ends_with($expense->description, '(Auto)'))
                    <span class="ml-auto bg-white/20 text-white text-xs font-bold px-2.5 py-1 rounded-full">🔁 Auto</span>
                @endif
            </div>
            <p class="text-5xl font-bold tracking-tight">₹{{ number_format($expense->amount, 2) }}</p>
            <p class="text-white/60 text-xs mt-2">Added {{ $expense->created_at->diffForHumans() }}</p>
        </div>

        {{-- Description --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Description</p>
            <p class="text-gray-800 text-base leading-relaxed">{{ $expense->description }}</p>
        </div>

        {{-- Meta row --}}
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-3 text-center">
                <p class="text-xs text-gray-400 uppercase tracking-wide">Category</p>
                <div class="flex items-center justify-center gap-1.5 mt-1.5">
                    <span class="text-base">{{ $catIcons[$expense->category] ?? '📦' }}</span>
                    <span class="text-sm font-bold text-gray-800 capitalize">{{ $expense->category }}</span>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-3 text-center">
                <p class="text-xs text-gray-400 uppercase tracking-wide">Date</p>
                <p class="text-sm font-bold text-gray-800 mt-1.5">{{ $expense->date->format('d M Y') }}</p>
            </div>
        </div>

        {{-- Receipt --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-3">Receipt</p>
            @if ($expense->receipt_image)
                @php $ext = strtolower(pathinfo($expense->receipt_image, PATHINFO_EXTENSION)); @endphp
                @if (in_array($ext, ['jpg','jpeg','png']))
                    <img src="{{ Storage::url($expense->receipt_image) }}"
                         alt="Receipt"
                         class="w-full rounded-xl border border-gray-100 object-cover max-h-64">
                @else
                    <a href="{{ Storage::url($expense->receipt_image) }}"
                       target="_blank"
                       class="flex items-center gap-3 bg-indigo-50 rounded-xl px-4 py-3 text-indigo-700 font-semibold text-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        View receipt PDF
                        <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                @endif
            @else
                <div class="flex items-center gap-3 text-gray-300 py-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm text-gray-400">No receipt attached.</p>
                    <a href="{{ route('expenses.edit', $expense) }}"
                       class="ml-auto text-indigo-500 text-xs font-semibold">Add one →</a>
                </div>
            @endif
        </div>

        @if ($expense->updated_at->ne($expense->created_at))
            <p class="text-xs text-gray-400 text-center">
                Last updated {{ $expense->updated_at->diffForHumans() }}
            </p>
        @endif

        {{-- Action buttons --}}
        <div class="flex gap-3 pt-1">
            <a href="{{ route('expenses.edit', $expense) }}"
               class="flex-1 flex items-center justify-center gap-2 bg-indigo-600 text-white font-bold py-4 rounded-2xl text-sm min-h-[52px] shadow-lg shadow-indigo-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Expense
            </a>
            <form action="{{ route('expenses.destroy', $expense) }}"
                  method="POST"
                  onsubmit="return confirm('Delete this expense? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-14 h-full flex items-center justify-center bg-red-50 text-red-500 rounded-2xl border border-red-100 min-h-[52px]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
        </div>

    </div>

</x-app-layout>
