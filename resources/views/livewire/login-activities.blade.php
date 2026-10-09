<div class="overflow-x-auto">
    <x-page-header title="User Login Activity" subtitle="View User Login Activity In the System">
    </x-page-header>
    {{-- Search --}}
    <div class="mb-3 flex items-center justify-end">
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

    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Guard</th>
                <th class="px-4 py-3">Login Time</th>
                <th class="px-4 py-3">Logout Time</th>
                <th class="px-4 py-3">IP Address</th>
                <th class="px-4 py-3">Status</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">
            @foreach ($this->loginActivities as $activity)
                <tr>
                    <td class="px-4 py-3">
                        {{ $activity->email }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $activity->guard }}
                    </td>

                    <td class="px-4 py-3 whitespace-nowrap">
                        {{ $activity->logged_in_at?->format('d M Y, H:i:s') }}
                    </td>

                    <td class="px-4 py-3 whitespace-nowrap">
                        {{ $activity->logged_out_at?->format('d M Y, H:i:s') ?? '—' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $activity->ip_address ?? '—' }}
                    </td>

                    <td class="px-4 py-3">
                        <span @class([
                            'rounded-full px-2 py-1 text-xs font-medium',
                            'bg-emerald-50 text-emerald-700' => $activity->status === 'logged_in',
                            'bg-gray-100 text-gray-600' => $activity->status !== 'logged_in',
                        ])>
                            {{ str_replace('_', ' ', ucfirst($activity->status)) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="border-t border-gray-100 px-4 py-3">
        {{ $this->loginActivities->onEachSide(0)->links() }}
    </div>
</div>
