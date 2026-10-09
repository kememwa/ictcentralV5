<div>
    <x-page-header
        title="User Login Activity"
        subtitle="View User Login Activity In the System"
    />

    {{-- Search --}}
    <div class="mb-4 flex justify-end">
        <div class="relative w-full sm:w-64">
            <svg
                class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"
                />
            </svg>

            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search user email..."
                class="w-full rounded-lg border border-slate-200 bg-white py-2 pl-9 pr-9
                       text-xs text-slate-700 placeholder:text-slate-400
                       focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
            />

            @if($search)
                <button
                    type="button"
                    wire:click="$set('search', '')"
                    class="absolute right-2.5 top-1/2 -translate-y-1/2
                           text-slate-400 transition hover:text-slate-600"
                    title="Clear search"
                    aria-label="Clear search"
                >
                    <svg
                        class="h-3.5 w-3.5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            @endif
        </div>
    </div>

    {{-- Fetch the paginated results once --}}
    @php
        $activities = $this->loginActivities;
    @endphp

    {{-- ================= DESKTOP TABLE ================= --}}
    <div class="hidden overflow-hidden rounded-xl border border-slate-200 bg-white lg:block">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Email</th>
                        <th class="px-4 py-3 font-semibold">Guard</th>
                        <th class="px-4 py-3 font-semibold">Login Time</th>
                        <th class="px-4 py-3 font-semibold">Logout Time</th>
                        <th class="px-4 py-3 font-semibold">IP Address</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($activities as $activity)
                        <tr wire:key="desktop-login-{{ $activity->id }}"
                            class="transition hover:bg-slate-50/70">

                            <td class="px-4 py-3 font-medium text-slate-700">
                                <span class="break-all">
                                    {{ $activity->email }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-slate-600">
                                {{ $activity->guard ?: '—' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                {{ $activity->logged_in_at?->format('d M Y, H:i:s') ?? '—' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                {{ $activity->logged_out_at?->format('d M Y, H:i:s') ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-slate-600">
                                {{ $activity->ip_address ?? '—' }}
                            </td>

                            <td class="px-4 py-3">
                                <span @class([
                                    'inline-flex items-center rounded-full px-2 py-1 text-[10px] font-semibold',
                                    'bg-emerald-50 text-emerald-700' => $activity->status === 'logged_in',
                                    'bg-slate-100 text-slate-600' => $activity->status !== 'logged_in',
                                ])>
                                    {{ str_replace('_', ' ', ucfirst($activity->status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-full bg-slate-100">
                                        <svg
                                            class="h-5 w-5 text-slate-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.7"
                                                d="M15 7a3 3 0 11-6 0 3 3 0 016 0zM5 20a7 7 0 0114 0M19 8v6m-3-3h6"
                                            />
                                        </svg>
                                    </div>

                                    <p class="text-sm font-semibold text-slate-700">
                                        {{ $search ? 'No matching login activity' : 'No login activity found' }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $search
                                            ? 'Try a different email address or clear your search.'
                                            : 'Login records will appear here when available.' }}
                                    </p>

                                    @if($search)
                                        <button
                                            type="button"
                                            wire:click="$set('search', '')"
                                            class="mt-3 text-xs font-medium text-blue-600 hover:text-blue-700"
                                        >
                                            Clear search
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Desktop Pagination --}}
        @if($activities->hasPages())
            <div class="border-t border-slate-100 px-4 py-3">
                {{ $activities->onEachSide(0)->links() }}
            </div>
        @endif
    </div>

    {{-- ================= MOBILE / TABLET CARDS ================= --}}
    <div class="space-y-3 lg:hidden">
        @forelse ($activities as $activity)
            <div
                wire:key="mobile-login-{{ $activity->id }}"
                class="rounded-xl border border-slate-200 bg-white p-4"
            >
                {{-- Email and Status --}}
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <p class="mb-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                            Email
                        </p>

                        <p class="break-all text-sm font-semibold text-slate-800">
                            {{ $activity->email }}
                        </p>
                    </div>

                    <span @class([
                        'inline-flex shrink-0 items-center rounded-full px-2 py-1 text-[10px] font-semibold',
                        'bg-emerald-50 text-emerald-700' => $activity->status === 'logged_in',
                        'bg-slate-100 text-slate-600' => $activity->status !== 'logged_in',
                    ])>
                        {{ str_replace('_', ' ', ucfirst($activity->status)) }}
                    </span>
                </div>

                <div class="my-3 border-t border-slate-100"></div>

                {{-- Guard --}}
                <div class="mb-3 flex items-start justify-between gap-4">
                    <span class="text-xs text-slate-500">Guard</span>
                    <span class="text-right text-xs font-medium text-slate-700">
                        {{ $activity->guard ?: '—' }}
                    </span>
                </div>

                {{-- Login Time --}}
                <div class="mb-3 flex items-start justify-between gap-4">
                    <span class="shrink-0 text-xs text-slate-500">Login Time</span>
                    <span class="text-right text-xs text-slate-700">
                        {{ $activity->logged_in_at?->format('d M Y, H:i:s') ?? '—' }}
                    </span>
                </div>

                {{-- Logout Time --}}
                <div class="mb-3 flex items-start justify-between gap-4">
                    <span class="shrink-0 text-xs text-slate-500">Logout Time</span>
                    <span class="text-right text-xs text-slate-700">
                        {{ $activity->logged_out_at?->format('d M Y, H:i:s') ?? '—' }}
                    </span>
                </div>

                {{-- IP Address --}}
                <div class="flex items-start justify-between gap-4">
                    <span class="shrink-0 text-xs text-slate-500">IP Address</span>
                    <span class="break-all text-right text-xs font-medium text-slate-700">
                        {{ $activity->ip_address ?? '—' }}
                    </span>
                </div>
            </div>
        @empty
            {{-- Mobile Empty State --}}
            <div class="rounded-xl border border-dashed border-slate-300 bg-white px-4 py-10 text-center">
                <div class="mx-auto mb-3 flex h-11 w-11 items-center justify-center rounded-full bg-slate-100">
                    <svg
                        class="h-5 w-5 text-slate-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.7"
                            d="M15 7a3 3 0 11-6 0 3 3 0 016 0zM5 20a7 7 0 0114 0M19 8v6m-3-3h6"
                        />
                    </svg>
                </div>

                <p class="text-sm font-semibold text-slate-700">
                    {{ $search ? 'No matching login activity' : 'No login activity found' }}
                </p>

                <p class="mx-auto mt-1 max-w-xs text-xs leading-5 text-slate-500">
                    {{ $search
                        ? 'No records match your search. Try another email address.'
                        : 'There are currently no login records to display.' }}
                </p>

                @if($search)
                    <button
                        type="button"
                        wire:click="$set('search', '')"
                        class="mt-3 rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50"
                    >
                        Clear search
                    </button>
                @endif
            </div>
        @endforelse

        {{-- Mobile Pagination --}}
        @if($activities->hasPages())
            <div class="rounded-xl border border-slate-200 bg-white px-3 py-3">
                {{ $activities->onEachSide(0)->links() }}
            </div>
        @endif
    </div>
</div>