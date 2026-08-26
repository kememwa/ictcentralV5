@props(['title' => null, 'subtitle' => null])

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    @if($title)
        <div class="flex items-center justify-between border-b border-slate-100 px-5 sm:px-6 py-4">
            <div>
                <h2 class="text-base font-semibold text-slate-900">{{ $title }}</h2>
                @if($subtitle)<p class="mt-0.5 text-xs text-slate-500">{{ $subtitle }}</p>@endif
            </div>
            @isset($actions)<div>{{ $actions }}</div>@endisset
        </div>
    @endif
    {{ $slot }}
</div>
