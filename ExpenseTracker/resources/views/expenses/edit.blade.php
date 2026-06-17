<x-app-layout>

    <div x-data="{
            submitting: false,
            selectedCategory: '{{ old('category', $expense->category) }}',
            fileName: ''
         }"
         class="max-w-lg mx-auto">

        {{-- Page header --}}
        <div class="flex items-center gap-3 px-4 pt-4 pb-3">
            <a href="{{ route('expenses.show', $expense) }}"
               class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 text-gray-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="flex-1">
                <h1 class="text-xl font-bold text-gray-900">Edit Expense</h1>
                <p class="text-xs text-gray-400">{{ $expense->date->format('d F Y') }}</p>
            </div>
            {{-- Quick delete --}}
            <form action="{{ route('expenses.destroy', $expense) }}" method="POST"
                  onsubmit="return confirm('Delete this expense?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-50 text-red-500 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
        </div>

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

        <form action="{{ route('expenses.update', $expense) }}"
              method="POST"
              enctype="multipart/form-data"
              @submit="submitting = true"
              autocomplete="off">
            @csrf
            @method('PUT')

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
                               value="{{ old('amount', $expense->amount) }}"
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
                            'food'           => ['icon' => '🍔', 'label' => 'Food',      'bg' => 'bg-orange-100'],
                            'transportation' => ['icon' => '🚗', 'label' => 'Transport', 'bg' => 'bg-blue-100'],
                            'entertainment'  => ['icon' => '🎬', 'label' => 'Fun',        'bg' => 'bg-purple-100'],
                            'health'         => ['icon' => '💊', 'label' => 'Health',     'bg' => 'bg-red-100'],
                            'shopping'       => ['icon' => '🛍️', 'label' => 'Shopping',  'bg' => 'bg-pink-100'],
                            'utilities'      => ['icon' => '💡', 'label' => 'Bills',      'bg' => 'bg-yellow-100'],
                            'other'          => ['icon' => '📦', 'label' => 'Other',      'bg' => 'bg-gray-100'],
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
                              class="w-full bg-transparent border-none outline-none focus:ring-0 text-base text-gray-900
                                     placeholder-gray-300 resize-none
                                     @error('description') text-red-500 @enderror">{{ old('description', $expense->description) }}</textarea>
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
                           value="{{ old('date', $expense->date->toDateString()) }}"
                           max="{{ now()->toDateString() }}"
                           class="w-full bg-transparent border-none outline-none focus:ring-0 text-base font-semibold text-gray-900
                                  @error('date') text-red-500 @enderror">
                    @error('date')
                        <p class="text-red-500 text-sm mt-1 border-t border-red-100 pt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Current receipt (if exists) --}}
                @if ($expense->receipt_image)
                    @php $ext = strtolower(pathinfo($expense->receipt_image, PATHINFO_EXTENSION)); @endphp
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-4">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-3">Current Receipt</p>
                        @if (in_array($ext, ['jpg','jpeg','png']))
                            <img src="{{ Storage::url($expense->receipt_image) }}"
                                 alt="Current receipt"
                                 class="w-full max-h-52 object-cover rounded-xl border border-gray-100">
                        @else
                            <a href="{{ Storage::url($expense->receipt_image) }}"
                               target="_blank"
                               class="flex items-center gap-2 bg-indigo-50 rounded-xl px-4 py-3 text-indigo-700 font-medium text-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                View receipt PDF
                            </a>
                        @endif
                        <p class="text-xs text-gray-400 mt-2">Upload a new file below to replace.</p>
                    </div>
                @endif

                {{-- Receipt upload card --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-3">
                        {{ $expense->receipt_image ? 'Replace Receipt' : 'Receipt' }}
                        <span class="font-normal text-gray-300">(optional)</span>
                    </label>
                    <label for="receipt_image"
                           class="flex items-center gap-3 border-2 border-dashed border-gray-200 rounded-xl px-4 py-3.5 cursor-pointer active:bg-gray-50 transition">
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
                    <input type="file" name="receipt_image" id="receipt_image"
                           accept=".jpg,.jpeg,.png,.pdf" class="sr-only"
                           @change="fileName = $event.target.files[0]?.name || ''">
                    @error('receipt_image')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Fixed submit --}}
            <div class="fixed bottom-16 inset-x-0 z-30 px-4 pb-3 pt-2 bg-gradient-to-t from-gray-50 to-transparent max-w-lg mx-auto">
                <button type="submit"
                        :disabled="submitting"
                        class="w-full bg-indigo-600 text-white font-bold py-4 rounded-2xl text-base min-h-[56px]
                               disabled:opacity-60 flex items-center justify-center gap-2 shadow-lg shadow-indigo-200">
                    <span x-show="!submitting">Update Expense</span>
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

</x-app-layout>
