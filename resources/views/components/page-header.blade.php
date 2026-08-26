@props(['title', 'subtitle' => null, 'icon' => null])

<div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
    <div class="flex items-start gap-3">
        @if($icon)
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                {!! $icon !!}
            </div>
        @endif
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">{{ $title }}</h1>
            @if($subtitle)
                <p class="mt-0.5 text-sm text-slate-500">{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    {{ $slot }} {{-- optional action buttons on the right --}}
    
       @isset($actions)
        <div class="flex flex-wrap items-center gap-2">
            {{ $actions }}
        </div>
    @endisset
 
</div>


