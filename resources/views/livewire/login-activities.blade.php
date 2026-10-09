<div class="overflow-x-auto">
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
