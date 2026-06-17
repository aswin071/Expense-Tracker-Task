<x-app-layout>

    <div class="px-4 pt-4 pb-6 max-w-lg mx-auto space-y-5">

        {{-- Header --}}
        <div class="flex items-center gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold text-gray-900">AI Bill Scanner</h1>
                    <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-full border border-amber-200 tracking-wide">
                        UPCOMING
                    </span>
                </div>
                <p class="text-sm text-gray-400 mt-0.5">Upload a bill and AI extracts the data</p>
            </div>
        </div>

        {{-- Coming soon hero --}}
        <div class="bg-gradient-to-br from-violet-600 to-purple-700 text-white rounded-3xl p-6 text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -translate-y-10 translate-x-10"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full translate-y-8 -translate-x-8"></div>
            <div class="relative z-10">
                <div class="text-6xl mb-3">🤖</div>
                <p class="text-lg font-bold">Smart Bill Recognition</p>
                <p class="text-violet-200 text-sm mt-2 leading-relaxed">
                    Upload any bill, receipt or invoice — AI will read it and<br>
                    auto-fill the expense form for you.
                </p>
                <div class="mt-4 inline-flex items-center gap-2 bg-white/15 px-4 py-2 rounded-full text-sm font-semibold">
                    <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
                    Coming Soon
                </div>
            </div>
        </div>

        {{-- Disabled upload zone --}}
        <div class="relative">
            <div class="border-2 border-dashed border-gray-200 rounded-3xl p-8 text-center bg-gray-50 opacity-50 select-none pointer-events-none">
                <div class="text-5xl mb-3">📎</div>
                <p class="font-semibold text-gray-600">Drop your bill here</p>
                <p class="text-sm text-gray-400 mt-1">Image, PDF or Excel — up to 10 MB</p>
                <div class="mt-5 flex items-center justify-center gap-3">
                    <div class="flex items-center gap-1.5 bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-600 shadow-sm">
                        📷 Camera
                    </div>
                    <div class="flex items-center gap-1.5 bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-600 shadow-sm">
                        🗂️ Browse files
                    </div>
                </div>
            </div>
            {{-- Overlay lock badge --}}
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="bg-white border border-gray-200 shadow-lg rounded-2xl px-5 py-3 flex items-center gap-2.5">
                    <span class="text-2xl">🔒</span>
                    <div>
                        <p class="text-sm font-bold text-gray-800">Feature Locked</p>
                        <p class="text-xs text-gray-400">Available in a future update</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- What it will do --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4 space-y-4">
            <p class="text-sm font-bold text-gray-700 uppercase tracking-wide">How it will work</p>

            @php
                $steps = [
                    ['icon' => '📤', 'color' => 'bg-violet-50 text-violet-600', 'title' => 'Upload your bill', 'desc' => 'Photo, scanned PDF, or an Excel export from your bank'],
                    ['icon' => '🧠', 'color' => 'bg-blue-50 text-blue-600',   'title' => 'AI reads the data', 'desc' => 'Extracts merchant, amount, date, and category automatically'],
                    ['icon' => '✏️', 'color' => 'bg-green-50 text-green-600', 'title' => 'Review & confirm', 'desc' => 'Edit any field before saving — you stay in control'],
                    ['icon' => '✅', 'color' => 'bg-indigo-50 text-indigo-600','title' => 'Expense logged', 'desc' => 'Saved instantly, receipt attached, no manual entry'],
                ];
            @endphp

            @foreach ($steps as $i => $step)
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-2xl {{ $step['color'] }} flex items-center justify-center text-xl flex-shrink-0">
                        {{ $step['icon'] }}
                    </div>
                    <div class="flex-1 pt-1">
                        <p class="text-sm font-semibold text-gray-800">{{ $step['title'] }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $step['desc'] }}</p>
                    </div>
                    <div class="w-6 h-6 rounded-full bg-gray-100 text-gray-400 text-xs font-bold flex items-center justify-center flex-shrink-0 mt-1">
                        {{ $i + 1 }}
                    </div>
                </div>
                @if (!$loop->last)
                    <div class="ml-5 border-l-2 border-dashed border-gray-100 h-3"></div>
                @endif
            @endforeach
        </div>

        {{-- Supported formats --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4">
            <p class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-3">Supported formats</p>
            <div class="grid grid-cols-3 gap-2">
                @foreach ([['🖼️','JPG / PNG','Photo of receipt'],['📄','PDF','Scanned or digital'],['📊','Excel / CSV','Bank statement']] as $fmt)
                    <div class="bg-gray-50 rounded-2xl px-3 py-3 text-center border border-gray-100 opacity-60">
                        <div class="text-2xl">{{ $fmt[0] }}</div>
                        <p class="text-xs font-bold text-gray-700 mt-1">{{ $fmt[1] }}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $fmt[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Notify me CTA (UI only) --}}
        <div class="bg-amber-50 border border-amber-100 rounded-2xl px-5 py-4 flex items-center gap-4">
            <div class="text-3xl flex-shrink-0">🔔</div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-amber-800">Get notified when it launches</p>
                <p class="text-xs text-amber-600 mt-0.5">We'll let you know as soon as it's ready.</p>
            </div>
            <button disabled
                    class="bg-amber-500 text-white text-xs font-bold px-3 py-2 rounded-xl opacity-60 cursor-not-allowed flex-shrink-0">
                Notify me
            </button>
        </div>

        <p class="text-xs text-center text-gray-400 pb-2">
            AI Bill Scanner is under development. No data is processed yet.
        </p>

    </div>

</x-app-layout>
