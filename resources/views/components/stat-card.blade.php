@props(['label', 'value', 'color' => 'indigo', 'icon' => null])

<div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">{{ $label }}</p>
            <p class="mt-2 text-2xl font-bold text-gray-900">{{ $value }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-{{ $color }}-100 text-{{ $color }}-600 flex items-center justify-center">
            <span class="text-lg">●</span>
        </div>
    </div>
</div>
