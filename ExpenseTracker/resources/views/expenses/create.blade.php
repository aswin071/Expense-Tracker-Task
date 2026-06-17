<x-app-layout>

    @php
        $hasSpending = collect($categoryTotals)->sum() > 0;
    @endphp

    <div x-data="{
            aware: {{ (!$hasSpending || $errors->any()) ? 'true' : 'false' }},
            submitting: false,
            selectedCategory: '{{ old('category', '') }}',
            fileName: ''
         }"
         class="max-w-lg mx-auto">

        {{-- Page header --}}
        <div class="flex items-center gap-3 px-4 pt-4 pb-3">
            <a href="{{ route('expenses.index') }}"
               class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 text-gray-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-xl font-bold text-gray-900">Add Expense</h1>
        </div>

        {{-- ── Awareness card (only when there IS spending this month) ── --}}
        <div x-show="!aware" x-cloak class="px-4 pb-4">
            <div class="bg-amber-50 border border-amber-200 rounded-3xl overflow-hidden">

                <div class="px-5 pt-5 pb-4">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-2xl">📊</span>
                        <div>
                            <h3 class="font-bold text-amber-900 text-base">This month's spending</h3>
                            <p class="text-xs text-amber-600">{{ now()->format('F Y') }}</p>
                        </div>
                    </div>

                    {{-- Only categories with actual spending --}}
                    <div class="space-y-2">
                        @php
                            $catIcons  = ['food'=>'🍔','transportation'=>'🚗','entertainment'=>'🎬','health'=>'💊','shopping'=>'🛍️','utilities'=>'💡','other'=>'📦'];
                            $catColors = ['food'=>'bg-orange-100','transportation'=>'bg-blue-100','entertainment'=>'bg-purple-100','health'=>'bg-red-100','shopping'=>'bg-pink-100','utilities'=>'bg-yellow-100','other'=>'bg-gray-100'];
                        @endphp
                        @foreach ($categoryTotals as $cat => $total)
                            @if ($total > 0)
                                <div class="flex items-center gap-3 bg-white rounded-2xl px-4 py-3">
                                    <div class="w-9 h-9 rounded-xl {{ $catColors[$cat] ?? 'bg-gray-100' }} flex items-center justify-center text-lg flex-shrink-0">
                                        {{ $catIcons[$cat] ?? '📦' }}
                                    </div>
                                    <span class="flex-1 text-sm font-medium text-gray-700 capitalize">{{ $cat }}</span>
                                    <span class="text-sm font-bold text-gray-900">₹{{ number_format($total, 0) }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Total row --}}
                    <div class="flex justify-between items-center mt-3 px-1">
                        <span class="text-xs text-amber-700 font-medium">Total this month</span>
                        <span class="text-base font-bold text-amber-900">₹{{ number_format(collect($categoryTotals)->sum(), 0) }}</span>
                    </div>
                </div>

                <div class="px-4 pb-4">
                    <button type="button"
                            @click="aware = true"
                            class="w-full bg-amber-500 text-white font-bold py-4 rounded-2xl text-base min-h-[56px]
                                   active:bg-amber-600 flex items-center justify-center gap-2">
                        I'm aware, proceed
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Expense form ── --}}
        <div x-show="aware" x-cloak>

            @if ($errors->any())
                <div class="mx-4 mb-3 bg-red-50 border border-red-200 rounded-2xl px-4 py-3">
                    <p class="text-sm font-semibold text-red-700 mb-1">Please fix the following:</p>
                    <ul class="text-sm text-red-600 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('expenses.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  @submit="submitting = true"
                  autocomplete="off">
                @csrf

                <div class="px-4 space-y-3 pb-36">

                    {{-- Amount card --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-4">
                        <label for="amount" class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">
                            Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="relative flex items-center">
                            <span class="absolute left-0 text-2xl font-bold text-gray-700 pointer-events-none select-none">₹</span>
                            <input type="number"
                                   name="amount"
                                   id="amount"
                                   step="0.01"
                                   min="0.01"
                                   inputmode="decimal"
                                   value="{{ old('amount') }}"
                                   placeholder="0.00"
                                   autofocus
                                   class="w-full pl-8 pr-2 text-3xl font-bold text-gray-900 bg-transparent border-none outline-none
                                          focus:ring-0 placeholder-gray-200
                                          @error('amount') text-red-500 @enderror">
                        </div>
                        @error('amount')
                            <p class="text-red-500 text-sm mt-2 border-t border-red-100 pt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Category card --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-4">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-3">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <input type="hidden" name="category" :value="selectedCategory">
                        @php
                            $catData = [
                                'food'           => ['icon' => '🍔', 'label' => 'Food',       'bg' => 'bg-orange-100'],
                                'transportation' => ['icon' => '🚗', 'label' => 'Transport',  'bg' => 'bg-blue-100'],
                                'entertainment'  => ['icon' => '🎬', 'label' => 'Fun',         'bg' => 'bg-purple-100'],
                                'health'         => ['icon' => '💊', 'label' => 'Health',      'bg' => 'bg-red-100'],
                                'shopping'       => ['icon' => '🛍️', 'label' => 'Shopping',   'bg' => 'bg-pink-100'],
                                'utilities'      => ['icon' => '💡', 'label' => 'Bills',       'bg' => 'bg-yellow-100'],
                                'other'          => ['icon' => '📦', 'label' => 'Other',       'bg' => 'bg-gray-100'],
                            ];
                        @endphp
                        <div class="grid grid-cols-4 gap-2">
                            @foreach ($catData as $val => $meta)
                                <button type="button"
                                        @click="selectedCategory = '{{ $val }}'"
                                        class="relative flex flex-col items-center gap-1.5 py-3 rounded-2xl border-2 transition-all"
                                        :class="selectedCategory === '{{ $val }}'
                                            ? 'border-indigo-500 bg-indigo-50'
                                            : 'border-transparent bg-gray-50 active:bg-gray-100'">
                                    <div class="w-10 h-10 rounded-xl {{ $meta['bg'] }} flex items-center justify-center text-xl">
                                        {{ $meta['icon'] }}
                                    </div>
                                    <span class="text-[11px] font-semibold text-gray-600 leading-tight">{{ $meta['label'] }}</span>
                                    {{-- Checkmark when selected --}}
                                    <span x-show="selectedCategory === '{{ $val }}'"
                                          class="absolute top-1.5 right-1.5 w-4 h-4 bg-indigo-500 rounded-full flex items-center justify-center">
                                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                </button>
                            @endforeach
                        </div>
                        @error('category')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description card --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-4">
                        <label for="description" class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description"
                                  id="description"
                                  rows="2"
                                  maxlength="500"
                                  placeholder="What did you spend on?"
                                  class="w-full bg-transparent border-none outline-none focus:ring-0 text-base text-gray-900
                                         placeholder-gray-300 resize-none
                                         @error('description') text-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1 border-t border-red-100 pt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Date card --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-4">
                        <label for="date" class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">
                            Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               name="date"
                               id="date"
                               value="{{ old('date', now()->toDateString()) }}"
                               max="{{ now()->toDateString() }}"
                               class="w-full bg-transparent border-none outline-none focus:ring-0 text-base font-semibold text-gray-900
                                      @error('date') text-red-500 @enderror">
                        @error('date')
                            <p class="text-red-500 text-sm mt-1 border-t border-red-100 pt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Receipt card --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-4">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-3">
                            Receipt <span class="font-normal text-gray-300">(optional)</span>
                        </label>
                        <label for="receipt_image"
                               class="flex items-center gap-3 border-2 border-dashed border-gray-200 rounded-xl px-4 py-3.5 cursor-pointer
                                      active:bg-gray-50 transition">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-700" x-text="fileName || 'Tap to attach receipt'"></p>
                                <p class="text-xs text-gray-400 mt-0.5">JPG, PNG or PDF · max 2 MB</p>
                            </div>
                            <svg x-show="fileName" class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </label>
                        <input type="file"
                               name="receipt_image"
                               id="receipt_image"
                               accept=".jpg,.jpeg,.png,.pdf"
                               class="sr-only"
                               @change="fileName = $event.target.files[0]?.name || ''">
                        @error('receipt_image')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Fixed submit --}}
                <div class="fixed bottom-16 inset-x-0 z-30 px-4 pb-3 pt-2 bg-gradient-to-t from-gray-50 to-transparent max-w-lg mx-auto">
                    <button type="submit"
                            :disabled="submitting || !selectedCategory"
                            class="w-full bg-indigo-600 text-white font-bold py-4 rounded-2xl text-base min-h-[56px]
                                   disabled:opacity-50 flex items-center justify-center gap-2 shadow-lg shadow-indigo-200">
                        <span x-show="!submitting">Save Expense</span>
                        <span x-show="submitting" x-cloak class="flex items-center gap-2">
                            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Saving…
                        </span>
                    </button>
                </div>

            </form>
        </div>

    </div>

</x-app-layout>
