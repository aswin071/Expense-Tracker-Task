<x-app-layout>

    <div class="px-4 pt-4 pb-6 max-w-lg mx-auto space-y-4">

        <h1 class="text-xl font-bold text-gray-900">Reports & Tools</h1>

        <a href="{{ route('reports.monthly') }}"
           class="flex items-center gap-4 bg-white rounded-2xl border border-gray-100 shadow-sm p-5 active:bg-gray-50 transition">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-2xl flex-shrink-0">
                📅
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-900">Monthly Report</p>
                <p class="text-sm text-gray-400 mt-0.5">Category breakdown, daily average &amp; charts</p>
            </div>
            <svg class="w-5 h-5 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        <a href="{{ route('coach.index') }}"
           class="flex items-center gap-4 bg-white rounded-2xl border border-gray-100 shadow-sm p-5 active:bg-gray-50 transition">
            <div class="w-12 h-12 rounded-2xl bg-yellow-50 flex items-center justify-center text-2xl flex-shrink-0">
                💡
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-900">Budget Coach</p>
                <p class="text-sm text-gray-400 mt-0.5">Spending vs your limits, colour-coded</p>
            </div>
            <svg class="w-5 h-5 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        <a href="{{ route('recurring.index') }}"
           class="flex items-center gap-4 bg-white rounded-2xl border border-gray-100 shadow-sm p-5 active:bg-gray-50 transition">
            <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center text-2xl flex-shrink-0">
                🔁
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-900">Recurring Expenses</p>
                <p class="text-sm text-gray-400 mt-0.5">Manage auto-logged monthly expenses</p>
            </div>
            <svg class="w-5 h-5 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        {{-- AI Bill Scanner — Upcoming --}}
        <a href="{{ route('scan.index') }}"
           class="flex items-center gap-4 bg-gradient-to-r from-violet-50 to-purple-50 border border-violet-100 rounded-2xl shadow-sm p-5 active:opacity-80 transition relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-violet-100/40 rounded-full translate-x-8 -translate-y-8 pointer-events-none"></div>
            <div class="w-12 h-12 rounded-2xl bg-violet-100 flex items-center justify-center text-2xl flex-shrink-0">
                🤖
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <p class="font-semibold text-gray-900">AI Bill Scanner</p>
                    <span class="bg-amber-100 text-amber-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-amber-200">
                        UPCOMING
                    </span>
                </div>
                <p class="text-sm text-gray-400 mt-0.5">Upload a bill — AI auto-fills the form</p>
            </div>
            <svg class="w-5 h-5 text-violet-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

    </div>

</x-app-layout>
