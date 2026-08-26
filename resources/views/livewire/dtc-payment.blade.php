<div class="space-y-6">

    {{-- ===== Page Header ===== --}}
    <x-page-header
        title="DTC – Mpesa Payments"
        subtitle="View all payments made to DTC via Mpesa and their statuses.">
    </x-page-header>

<x-data-card title="DTC Mpesa Payments" subtitle="All payments made to DTC via Mpesa.">
        <x-slot name="actions">
            {{-- Search + filter --}}
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <div class="relative flex-1 sm:w-72">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                    </svg>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search devices…"
                        class="w-full pl-10 pr-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                @if($search)
                    <button wire:click="$set('search', ''); $set('actionFilter', '')"
                        class="inline-flex items-center justify-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50">
                        Clear
                    </button>
                @endif
            </div>
        </x-slot>
    

        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-gray-50 text-left uppercase tracking-wider text-[11px] text-gray-500">
                    <tr>
                        <th class="px-3 py-2">Order Number</th>
                        <th class="px-3 py-2">Cu. Phone Number</th>
                        <th class="px-3 py-2 hidden xl:table-cell">Amount</th>
                        <th class="px-3 py-2 hidden xl:table-cell">Status</th>
                        <th class="px-3 py-2 hidden xl:table-cell">Mpesa Receipt Number</th>
                        <th class="px-3 py-2 hidden xl:table-cell">Result Description</th>
                        <th class="px-3 py-2">Paid At</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($mpesa_transactions as $transaction)
                        <tr class="hover:bg-gray-50 transition">
                            
                            {{-- Order Number --}}
                            <td class="px-3 py-2">
                                <p class="font-medium text-gray-900 truncate max-w-[140px]">
                                    {{ $transaction->order_number }}
                                </p>
                            </td>

                            {{-- Customer Phone Number --}}
                            <td class="px-3 py-2 capitalize text-gray-700">
                                {{ $transaction->phone_number }}
                            </td>

                            {{-- Amount --}}
                            <td class="px-3 py-2 hidden xl:table-cell text-gray-500">
                                {{ $transaction->amount }}
                            </td>

                            {{-- Status --}}
                            <td class="px-3 py-2 hidden xl:table-cell text-gray-700">
                                {{ $transaction->status }}
                            </td>

                            {{-- Mpesa Receipt Number --}}
                            <td class="px-3 py-2 hidden xl:table-cell text-gray-700">
                                {{ $transaction->mpesa_receipt_number ?? 'null' }}
                            </td>

                            {{-- Comment (with tooltip for long text) --}}
                        <td class="px-3 py-2">
                            @if($transaction->result_description)
                                <div class="group relative">
                                    <div class="flex items-start gap-1.5">
                                        <svg class="w-3 h-3 text-blue-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-slate-600 text-[11px] leading-relaxed line-clamp-2 break-words max-w-[200px]">
                                                {{ $transaction->result_description }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    {{-- Tooltip for long comments --}}
                                    @if(strlen($transaction->result_description) > 80)
                                        <div class="absolute bottom-full left-0 mb-2 hidden group-hover:block z-20">
                                            <div class="bg-gray-900 text-white text-xs rounded-lg p-2 max-w-xs shadow-lg">
                                                <p class="text-[11px] leading-relaxed">{{ $transaction->result_description }}</p>
                                                <div class="absolute left-4 top-full w-2 h-2 bg-gray-900 transform rotate-45 -mt-1"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <span class="text-slate-400 text-[11px] italic">No description available</span>
                            @endif
                        </td>

                            {{-- Paid At --}}
                            <td class="px-3 py-2 text-gray-500">
                                {{ $transaction->paid_at ?? 'null' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center">
                                <div class="flex flex-col items-center gap-2 text-gray-500">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0l-2 7H6l-2-7m16 0H4"/>
                                    </svg>
                                    <p class="font-medium text-gray-700 text-sm">No transactions found</p>
                                    <p class="text-[11px]">Try adjusting your search or add a new transaction</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===== MOBILE / TABLET CARDS (below lg) ===== --}}
        <div class="lg:hidden divide-y divide-gray-100">
            @forelse ($mpesa_transactions as $transaction)
                <div class="p-4 hover:bg-gray-50 transition">
                    {{-- Header row --}}
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3 min-w-0">
                            @php

                                $statusClasses = match($transaction->status) {

                                    'SUCCESS' =>
                                        'bg-emerald-50 text-emerald-700 ring-emerald-200',

                                    'PENDING' =>
                                        'bg-yellow-50 text-yellow-700 ring-yellow-200',
                                        

                                    'FAILED' =>
                                        'bg-rose-50 text-rose-700 ring-rose-200',
                                        

                                    'CANCELLED' =>
                                        'bg-gray-100 text-gray-700 ring-gray-200',
                                        

                                    'TIMEOUT' =>
                                        'bg-orange-50 text-orange-700 ring-orange-200',
                                        

                                    default =>
                                        'bg-slate-100 text-slate-700 ring-slate-200',
                                        
                                };

                            @endphp

                            <span class="inline-flex items-center justify-center whitespace-nowrap min-w-[95px]
                                px-3 py-1 rounded-full text-xs font-semibold ring-1
                                {{ $statusClasses }}">

                                {{ $transaction->status }}

                            </span>
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-900 truncate">{{ $transaction->order_number}}</p>
                            </div>
                        </div>
                        
                    </div>

                    {{-- Detail grid --}}
                    <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
                        <div>
                            <dt class="text-gray-500">Amount</dt>
                            <dd class="font-medium text-gray-900">KES {{ number_format($transaction->amount ?? 0, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Cu. Phone Number</dt>
                            <dd class="font-mono text-gray-700">{{ $transaction->phone_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Mpesa Receipt Number</dt>
                            <dd class="text-gray-700 truncate">{{ $transaction->mpesa_receipt_number}}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Status</dt>
                            <dd class="text-gray-700 truncate">{{ $transaction->status ?? 'Pending' }}</dd>
                        </div>
                        <div class="col-span-2">
                            <dt class="text-gray-500">Paid At</dt>
                            <dd class="font-mono text-gray-700 truncate">{{ $transaction->paid_at}}</dd>
                        </div>
                    </dl>
                </div>
            @empty
                <div class="py-16 text-center">
                    <p class="font-medium text-gray-700">No devices found</p>
                    <p class="text-xs text-gray-500 mt-1">Try adjusting your search or add a new device</p>
                </div>
            @endforelse
        </div>

        @if($mpesa_transactions->hasPages())
            <div class="px-4 sm:px-6 py-4 border-t border-gray-100">
                {{ $mpesa_transactions->links() }}
            </div>
        @endif
        
</x-data-card>
</div>

  