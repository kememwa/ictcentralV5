<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

    @if($title)
        <div class="flex flex-col gap-3 border-b border-slate-100 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">

            {{-- Title --}}
            <div class="min-w-0">
                <h2 class="truncate text-base font-semibold text-slate-900">
                    {{ $title }}
                </h2>

                @if($subtitle)
                    <p class="mt-0.5 text-xs text-slate-500">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>

            {{-- Actions --}}
            @isset($actions)
                <div class="w-full shrink-0 sm:w-auto">
                    {{ $actions }}
                </div>
            @endisset

        </div>
    @endif

    {{ $slot }}

</div>