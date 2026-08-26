<div class="rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-3 text-white shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-2">
        <p class="text-sm font-medium">Hi {{ auth()->user()->name }} 👋</p>
        <p class="text-xs text-indigo-100">{{ now()->format('l, F j, Y') }}</p>
    </div>
</div>
