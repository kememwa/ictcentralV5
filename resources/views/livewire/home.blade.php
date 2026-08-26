<div class="space-y-6">

    {{-- ===== Welcome Header ===== --}}
    <div class="rounded-2xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 p-6 sm:p-8 text-white shadow-lg">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-indigo-200">{{ now()->format('l, F j, Y') }}</p>
                <h1 class="mt-1 text-2xl sm:text-3xl font-bold tracking-tight">
                    {{ auth()->user()->name }} 👋
                </h1>
                <p class="mt-1 text-sm text-indigo-100">Here's what's happening with your account today.</p>
            </div>
            <button wire:click="reportIssue()"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-white/15 hover:bg-white/25 backdrop-blur px-4 py-2.5 text-sm font-medium text-white ring-1 ring-white/20 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.732-3L13.732 4a2 2 0 00-3.464 0L3.268 16A2 2 0 005 19z"/>
                </svg>
                Report an issue
            </button>

        </div>
    </div>

    {{-- ===== Quick Stats ===== --}}
    <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
        @php
            $devices = auth()->user()->devices ?? collect();
            $devicesWithIssues = $devices->filter(fn($d) => $d->issues()->where('status', 'Active')->exists());
            $diviceWithIssues = $devicesWithIssues->count();
            $active = $devices->filter(fn($d) => $d->line_manager_approval && $d->user_accepted)->count();
            $pending = $devices->filter(fn($d) => !$d->line_manager_approval || !$d->user_accepted)->count();
            $totalValue = $devices->sum('value');
        @endphp

        @foreach ([
            ['label' => 'Total Devices', 'value' => $devices->count(), 'icon' => 'M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2M9 17H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4M9 17h6', 'tone' => 'indigo', 'hasList' => false],
            ['label' => 'Active', 'value' => $active, 'icon' => 'M5 13l4 4L19 7', 'tone' => 'emerald', 'hasList' => false],
            ['label' => 'Pending', 'value' => $pending, 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'tone' => 'amber', 'hasList' => false],
            ['label' => 'Devices with Issues', 'value' => $diviceWithIssues, 'items' => $devicesWithIssues, 'tone' => 'red', 'hasList' => true],
        ] as $stat)
            <div class="rounded-xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-medium text-slate-500">{{ $stat['label'] }}</p>
                    @if(!$stat['hasList'])
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-{{ $stat['tone'] }}-50 text-{{ $stat['tone'] }}-600 group-hover:scale-110 transition-transform">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                            </svg>
                        </div>
                    @else
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-{{ $stat['tone'] }}-50 text-{{ $stat['tone'] }}-600 group-hover:scale-110 transition-transform">
                            <span class="font-bold text-sm">{{ $stat['value'] }}</span>
                        </div>
                    @endif
                </div>
                
                @if(!$stat['hasList'])
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $stat['value'] }}</p>
                @else
                    <div class="mt-2 space-y-1.5">
                        @forelse($stat['items'] as $device)
                            <div class="flex items-center gap-2 group/device">
                                <div class="w-1.5 h-1.5 rounded-full bg-rose-500 group-hover/device:scale-125 transition-transform"></div>
                                <p class="text-sm text-slate-700 truncate hover:text-violet-600 transition-colors cursor-pointer" 
                                title="{{ $device->name }}"
                                wire:click="viewDeviceDetails({{ $device->id }})">
                                    {{ $device->name }}
                                </p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400 italic">No devices with issues</p>
                        @endforelse
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-5 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-900">My Devices</h3>
                <p class="text-xs text-gray-500">Company-issued devices assigned to you</p>
            </div>
        </div>

        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
            {{ $devices->count() }} {{ Str::plural('device', $devices->count()) }}
        </span>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-6 py-3">Device</th>
                    <th class="px-6 py-3">Model Number</th>
                    <th class="px-6 py-3">Tag Number</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($devices as $device)
                    @php
                        if (!$device->line_manager_approval) {
                            $status = ['label' => 'Awaiting manager', 'color' => 'amber'];
                        } elseif (!$device->user_accepted) {
                            $status = ['label' => 'Awaiting you',     'color' => 'orange'];
                        } else {
                            $status = ['label' => 'In use',           'color' => 'emerald'];
                        }
                        $canAccept = $device->line_manager_approval && !$device->user_accepted;
                    @endphp

                    <tr class="hover:bg-gray-50 transition">
                        {{-- Device --}}
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-semibold text-xs">
                                    {{ strtoupper(substr($device->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 ">{{ $device->name }}</p>
                                    <p class="text-xs text-gray-500 capitalize">{{ $device->type }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-3 text-gray-700">{{ $device->model ?? '—' }}</td>

                        <td class="px-6 py-3">
                            <span class="font-mono text-xs px-2 py-1 rounded-md bg-gray-100 text-gray-700">
                                {{ $device->tag_number ?? '—' }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-{{ $status['color'] }}-50 text-{{ $status['color'] }}-700 ring-1 ring-{{ $status['color'] }}-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-{{ $status['color'] }}-500 {{ $canAccept ? 'animate-pulse' : '' }}"></span>
                                {{ $status['label'] }}
                            </span>
                        </td>

                        {{-- Action --}}
                        <td class="px-6 py-3 text-right">
                            <div x-data="{ open: false }">
                                <button @click="open = true"
                                    @disabled(!$canAccept)
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:shadow-md transition disabled:bg-none disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed disabled:shadow-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Accept
                                </button>

                                {{-- Confirm Modal (teleported) --}}
                                <template x-teleport="body">
                                    <div x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                                        <div x-show="open" x-transition.opacity @click="open = false"
                                             class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

                                        <div x-show="open" x-transition.scale.origin.center
                                             @keydown.escape.window="open = false"
                                             class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">

                                            <div class="p-6">
                                                <div class="flex items-center gap-3 mb-4">
                                                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h3 class="text-base font-semibold text-gray-900">Accept Device</h3>
                                                        <p class="text-xs text-gray-500">{{ $device->name }} · {{ $device->tag_number }}</p>
                                                    </div>
                                                </div>

                                                <p class="text-sm text-gray-600">
                                                    By accepting, you confirm responsibility for this device and that it has been received in good condition.
                                                </p>
                                            </div>

                                            <div class="flex justify-end gap-2 px-6 py-4 bg-gray-50 border-gray-100">
                                                <button @click="open = false"
                                                    class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-white">
                                                    Cancel
                                                </button>
                                                <button @click="open = false; $nextTick(() => $wire.acceptDevice({{ $device->id }}))"
                                                    class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 rounded-lg shadow-sm">
                                                    Confirm
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center">
                            <div class="flex flex-col items-center gap-2 text-gray-500">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <p class="font-medium text-gray-700">No devices assigned</p>
                                <p class="text-xs">Devices issued to you will appear here</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    {{-- ===== Knowledge Base (collapsible, compact) ===== --}}
    <div x-data="{ showKnowledgeBase: false, kbTab: 'all' }" class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <button @click="showKnowledgeBase = !showKnowledgeBase"
            class="flex w-full items-center justify-between px-5 sm:px-6 py-4 hover:bg-slate-50 transition">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="text-left">
                    <h2 class="text-base font-semibold text-slate-900">Knowledge Base</h2>
                    <p class="text-xs text-slate-500">Training videos & resources</p>
                </div>
            </div>
            <svg :class="showKnowledgeBase ? 'rotate-180' : ''" class="h-5 w-5 text-slate-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="showKnowledgeBase" x-collapse class="border-t border-slate-100 p-5 sm:p-6">
            {{-- Keep your existing tabs + video grid here, unchanged logic --}}
            <!-- Category Tabs for Knowledge Base -->
            <div class="mb-6 border-b border-gray-200" x-data="{ kbTab: 'all' }">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                    <li class="mr-2">
                        <a href="#" @click.prevent="kbTab = 'all'" 
                           :class="{ 'text-blue-600 border-b-2 border-blue-600': kbTab === 'all', 'text-gray-500 hover:text-gray-700': kbTab !== 'all' }"
                           class="inline-block p-3 rounded-t-lg">
                            All Videos
                        </a>
                    </li>
                    <li class="mr-2">
                        <a href="#" @click.prevent="kbTab = 'getting-started'"
                           :class="{ 'text-blue-600 border-b-2 border-blue-600': kbTab === 'getting-started', 'text-gray-500 hover:text-gray-700': kbTab !== 'getting-started' }"
                           class="inline-block p-3 rounded-t-lg">
                            Getting Started
                        </a>
                    </li>
                    <li class="mr-2">
                        <a href="#" @click.prevent="kbTab = 'tutorials'"
                           :class="{ 'text-blue-600 border-b-2 border-blue-600': kbTab === 'tutorials', 'text-gray-500 hover:text-gray-700': kbTab !== 'tutorials' }"
                           class="inline-block p-3 rounded-t-lg">
                            Tutorials
                        </a>
                    </li>
                    <li class="mr-2">
                        <a href="#" @click.prevent="kbTab = 'best-practices'"
                           :class="{ 'text-blue-600 border-b-2 border-blue-600': kbTab === 'best-practices', 'text-gray-500 hover:text-gray-700': kbTab !== 'best-practices' }"
                           class="inline-block p-3 rounded-t-lg">
                            Best Practices
                        </a>
                    </li>
                    <li class="mr-2">
                        <a href="#" @click.prevent="kbTab = 'faqs'"
                           :class="{ 'text-blue-600 border-b-2 border-blue-600': kbTab === 'faqs', 'text-gray-500 hover:text-gray-700': kbTab !== 'faqs' }"
                           class="inline-block p-3 rounded-t-lg">
                            FAQs
                        </a>
                    </li>
                </ul>
                <!-- All Videos -->
                <div x-show="kbTab === 'all'" class="mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                   
                    <!-- Video Card Component - Modern & User Friendly -->
                    <div x-data="{ open: false }" x-init="$watch('open', value => {
                        if (value) {
                            document.body.classList.add('overflow-hidden');
                            // Small delay to ensure modal is open before playing
                            setTimeout(() => {
                                if ($refs.video) $refs.video.play();
                            }, 100);
                        } else {
                            document.body.classList.remove('overflow-hidden');
                            if ($refs.video) {
                                $refs.video.pause();
                                $refs.video.currentTime = 0; // Reset video to start
                            }
                        }
                    })" class="group relative"
                    @contextmenu.prevent  
                    @keydown.down.prevent  
                    @keydown.s.down.prevent>


                    
                    <!-- Card with enhanced visual design -->
                    <div @click="open = true"
                        class="bg-white rounded-2xl overflow-hidden 
                                shadow-md hover:shadow-xl transition-all duration-300 
                                cursor-pointer transform hover:-translate-y-1 
                                border border-gray-100">
                        
                        <!-- Thumbnail Container with Gradient Overlay -->
                        <div class="relative overflow-hidden bg-gray-900 aspect-video">
                            <!-- Image with zoom effect on hover -->
                            <img src="{{ asset('images/Kim-Fay entrance.png') }}"
                                alt="Company Overview"
                                class="w-full h-48 object-cover transition-transform duration-700 
                                        group-hover:scale-110 opacity-90 group-hover:opacity-100">
                            
                            <!-- Gradient Overlay - Improves text visibility -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                            
                            <!-- Play Button - Centered with animation -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center
                                            shadow-xl transform transition-all duration-300 
                                            group-hover:scale-110 group-hover:bg-blue-700
                                            group-hover:shadow-2xl ring-4 ring-white/30">
                                    <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                            </div>
                            
                        </div>
                        
                        <!-- Content Section -->
                        <div class="p-5">
                            <h4 class="font-semibold text-gray-900 text-base mb-1 
                                    line-clamp-1 group-hover:text-blue-600  
                                    transition-colors">
                                Welcome to the Company
                            </h4>
                            
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                Learn about our culture and values in this comprehensive introduction video
                            </p>
                            
                            <!-- Meta Information -->
                            <div class="flex items-center justify-between text-xs">
                                
                                <!-- Interactive Watch Button -->
                                <span class="text-blue-600 font-medium 
                                        opacity-0 group-hover:opacity-100 transition-opacity">
                                    Watch now →
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Modal - Enhanced with better UX -->
                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
                        @click.self="open = false"
                        @keydown.escape.window="open = false"
                        x-cloak>
                        
                        <!-- Modal Container with animation -->
                        <div x-show="open"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="relative w-full max-w-5xl">
                            
                            <!-- Close Button - Improved design -->
                            <button @click="open = false"
                                    class="absolute -top-12 right-0 text-white/80 hover:text-white
                                        transition-colors duration-200 z-10 flex items-center space-x-2
                                        bg-black/20 backdrop-blur-sm px-4 py-2 rounded-full
                                        border border-white/20 hover:bg-black/40">
                                <span>Close</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            
                            <!-- Video Container with elegant styling -->
                            <div class="bg-gray-900 rounded-2xl overflow-hidden shadow-2xl 
                                        border border-white/10">
                                
                                <!-- Video Title Bar -->
                                <div class="bg-gray-800/50 backdrop-blur-sm px-6 py-4 
                                            border-b border-white/10 flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 
                                                    rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-white font-semibold">Welcome to the Company</h3>
                                            <p class="text-xs text-gray-400">Learn about our culture and values</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Video Player -->
                                <div class="relative bg-black">
                                    <video x-ref="video"
                                        controls
                                        controlslist="nodownload noremoteplayback"
                                        disablepictureinpicture
                                        playsinline
                                        preload="metadata"
                                        class="w-full aspect-video"
                                        @ended="console.log('Video ended')">
                                        <source src="storage/videos/who-we-are-video.mp4" type="video/mp4">
                                        <!-- Fallback message -->
                                        <div class="absolute inset-0 flex items-center justify-center bg-gray-900">
                                            <p class="text-white">Your browser does not support the video tag.</p>
                                        </div>
                                    </video>
                                </div>          
                            </div>
                        </div>
                    </div>
                </div>

                    <!-- Required Styles (add to your head) -->
                    <style>
                        [x-cloak] { display: none !important; }
                        .line-clamp-1 {
                            display: -webkit-box;
                            -webkit-line-clamp: 1;
                            -webkit-box-orient: vertical;
                            overflow: hidden;
                        }
                        .line-clamp-2 {
                            display: -webkit-box;
                            -webkit-line-clamp: 2;
                            -webkit-box-orient: vertical;
                            overflow: hidden;
                        }
                    </style>
            
            </div>

            </div>

                <!-- Getting Started Videos -->
                <div x-show="kbTab === 'getting-started'" class="mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Getting Started Video Cards -->
                        <div class="bg-gray-50 rounded-lg overflow-hidden hover:shadow-md transition">
                            <div class="relative group">
                                <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                                     alt="Getting Started" 
                                     class="w-full h-36 object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <button class="bg-white rounded-full p-2 shadow-lg">
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" />
                                        </svg>
                                    </button>
                                </div>
                                <span class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">4:30</span>
                            </div>
                            <div class="p-3">
                                <h4 class="font-medium text-sm">First Day Orientation</h4>
                                <p class="text-xs text-gray-500 mt-1">What to expect on your first day</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tutorials Videos -->
                <div x-show="kbTab === 'tutorials'" class="mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Tutorial Video Cards -->
                        <div class="bg-gray-50 rounded-lg overflow-hidden hover:shadow-md transition">
                            <div class="relative group">
                                <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                                     alt="Tutorial" 
                                     class="w-full h-36 object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <button class="bg-white rounded-full p-2 shadow-lg">
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" />
                                        </svg>
                                    </button>
                                </div>
                                <span class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">10:45</span>
                            </div>
                            <div class="p-3">
                                <h4 class="font-medium text-sm">Advanced Features Tutorial</h4>
                                <p class="text-xs text-gray-500 mt-1">Deep dive into advanced features</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Best Practices Videos -->
                <div x-show="kbTab === 'best-practices'" class="mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Best Practices Video Cards -->
                        <div class="bg-gray-50 rounded-lg overflow-hidden hover:shadow-md transition">
                            <div class="relative group">
                                <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                                     alt="Best Practices" 
                                     class="w-full h-36 object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <button class="bg-white rounded-full p-2 shadow-lg">
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" />
                                        </svg>
                                    </button>
                                </div>
                                <span class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">7:20</span>
                            </div>
                            <div class="p-3">
                                <h4 class="font-medium text-sm">Data Protection Best Practices</h4>
                                <p class="text-xs text-gray-500 mt-1">How to handle sensitive data</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQs Videos -->
                <div x-show="kbTab === 'faqs'" class="mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- FAQs Video Cards -->
                        <div class="bg-gray-50 rounded-lg overflow-hidden hover:shadow-md transition">
                            <div class="relative group">
                                <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                                     alt="FAQs" 
                                     class="w-full h-36 object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <button class="bg-white rounded-full p-2 shadow-lg">
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" />
                                        </svg>
                                    </button>
                                </div>
                                <span class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">6:15</span>
                            </div>
                            <div class="p-3">
                                <h4 class="font-medium text-sm">Common Issues & Solutions</h4>
                                <p class="text-xs text-gray-500 mt-1">Troubleshooting common problems</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div
        x-data="{ isOpen: false }"
        x-on:open-report-modal.window="isOpen = true"
        x-on:close-report-modal.window="isOpen = false"
        x-init="$watch('isOpen', v => document.body.classList.toggle('overflow-hidden', v))"
    >
        <div
            x-show="isOpen"
            x-transition.opacity
            @keydown.escape.window="isOpen = false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
        >
            <div
                @click.away="isOpen = false"
                x-transition.scale.origin.center
                class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden"
            >
                {{-- Header --}}
                <div class="flex items-start justify-between p-6 border-b border-gray-100">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Report Issue</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Report an issue with your device</p>
                    </div>
                    <button @click="isOpen = false"
                        class="text-gray-400 hover:bg-gray-100 hover:text-gray-700 rounded-full w-8 h-8 inline-flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Form --}}
                <form wire:submit.prevent="sendReport" class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">

                    <div>
                        <label class="text-xs font-medium text-gray-700">Affected Device</label>
                        <select wire:model="affectedDeviceId"
                                class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Device</option>
                                @foreach($userDevices as $device)
                                    <option value="{{ $device->id }}">
                                        {{ $device->name }} ({{ $device->type }})
                                    </option>
                                @endforeach
                        </select>
                        @error('type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>


                    <div>
                        <label class="text-xs font-medium text-gray-700">Issue Category</label>
                        <select wire:model="categoryKey"
                                class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">Select issue</option>
                                @foreach($discrepancyCategories as $key => $category)
                                    <option value="{{ $key }}">{{ $category }}</option>
                                @endforeach
                        </select>
                        @error('type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>


                    <div>
                        <label class="text-xs font-medium text-gray-700">Your Comment</label>
                        <textarea wire:model="comments"
                                    rows="4"
                                    class="w-full p-2 text-sm border rounded-lg"
                                    placeholder="Describe the issue..."></textarea>
                        @error('comments') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                        <button type="button" @click="isOpen = false"
                            class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm">
                            <span wire:loading.remove wire:target="sendReport">Send Report</span>
                            <span wire:loading wire:target="sendReport">Saving…</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>


