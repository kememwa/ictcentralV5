<div>
    
    <aside
        x-data="{
            inventoryOpen: $persist(false).as('sb_inventoryOpen'),
            mtMgmtOpen:    $persist(false).as('sb_mtMgmtOpen'),
            userMgmtOpen:  $persist(false).as('sb_userMgmtOpen'),
            casualOpen:    $persist(false).as('sb_casualOpen'),
            paymentOpen:   $persist(false).as('sb_paymentOpen'),
            orgSetupOpen:  $persist(false).as('sb_orgSetupOpen'),
        }"
        @click.outside="if (window.innerWidth < 640) sidebarOpen = false"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full sm:translate-x-0'"
        class="fixed inset-y-0 left-0 z-30 flex w-64 flex-col border-r border-slate-200 bg-white transition-transform duration-200 ease-in-out"
    >




    {{-- Brand --}}
    <div class="flex h-16 items-center gap-3 border-b border-slate-200 px-4">
        <img src="{{ asset('images/kimfay.png') }}" alt="KimFay" class="h-10 w-20 rounded-lg object-contain">
        <div class="flex min-w-0 flex-col leading-tight">
            <span class="truncate text-sm font-semibold text-slate-900">Kim-Fay EA LTD</span>
            <span class="truncate text-xs text-slate-500">ICT Central</span>
        </div>

        {{-- Close button (mobile only) --}}
        <button
            @click="sidebarOpen = false"
            class="ml-auto rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700 sm:hidden"
            aria-label="Close menu"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>



    {{-- Nav --}}
    <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-5">

        {{-- ===== Section: Main ===== --}}
        <div>
            <p class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Main</p>

            {{-- Dashboard --}}
            <a wire:navigate href="{{ route('home') }}"
               class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg class="h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>
        </div>

        {{-- ===== Section: Operations ===== --}}
        <div>
            <p class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Operations</p>

            {{-- Inventory (collapsible) --}}
            <button @click="inventoryOpen = !inventoryOpen"
                    :aria-expanded="inventoryOpen"
                    class="group flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-slate-900">
                <svg class="h-5 w-5 text-slate-400 group-hover:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span class="flex-1 text-left">ICT Asset Inventory</span>
                <svg :class="inventoryOpen ? 'rotate-180' : ''" class="h-4 w-4 text-slate-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <ul x-show="inventoryOpen" x-collapse class="mt-1 space-y-0.5 pl-9">
                <li><a wire:navigate href="{{ route('inventory') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900"><span>🗂️</span>Overview</a></li>
                <li><a wire:navigate href="{{ route('pendingapproval') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900"><span>⏳</span>Pending Approval</a></li>
                <li><a wire:navigate href="{{ route('devicehistory') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900"><span>📜</span>Device History</a></li>
                <li><a wire:navigate href="{{ route('inventory-analytics') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900"><span>📊</span>Inventory Analytics</a></li>
                <li>
                    <a wire:navigate href="{{ route('printers-tonner') }}" 
                    class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900">
                        <span>🖨️</span>
                        Printers & Toner
                    </a>
                </li>
            </ul>

            {{-- MT-Management (NEW, collapsible) --}}
            <button @click="mtMgmtOpen = !mtMgmtOpen"
                    :aria-expanded="mtMgmtOpen"
                    class="group mt-1 flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-slate-900">
                <svg class="h-5 w-5 text-slate-400 group-hover:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="flex-1 text-left">MT-Management</span>
                <svg :class="mtMgmtOpen ? 'rotate-180' : ''" class="ml-1 h-4 w-4 text-slate-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <ul x-show="mtMgmtOpen" x-collapse class="mt-1 space-y-0.5 pl-9">
                <li><a href="#" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900"><span>🏷️</span>MT Overview</a></li>
                <li><a href="#" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900"><span>📦</span>Assignments</a></li>
                <li><a href="#" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900"><span>🛠️</span>Maintenance</a></li>
                <li><a href="#" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900"><span>📈</span>Reports</a></li>
            </ul>

            {{-- Casual Workforce (collapsible) --}}
            <button @click="casualOpen = !casualOpen"
                    :aria-expanded="casualOpen"
                    class="group mt-1 flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-slate-900">
                
                <svg class="h-5 w-5 text-slate-400 group-hover:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5V4H2v16h5m10 0v-6a4 4 0 00-8 0v6m8 0H9"/>
                </svg>

                <span class="flex-1 text-left">Casual Workforce</span>

                <svg :class="casualOpen ? 'rotate-180' : ''"
                    class="h-4 w-4 text-slate-400 transition-transform"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <ul x-show="casualOpen" x-collapse class="mt-1 space-y-0.5 pl-9">
                <li>
                    <a wire:navigate href="{{route('casual-workforce')}}"
                    class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900">
                        <span>📝</span> Create Requisition
                    </a>
                </li>

                <li>
                    <a wire:navigate href="{{route('requisition')}}"
                    class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900">
                        <span>✅</span> HOD Approve Request
                    </a>
                </li>

                <li>
                    <a wire:navigate href="{{route('hr.casual.manage')}}"
                    class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900">
                        <span>👥</span> HR Casual MGMT
                    </a>
                </li>

                <li>
                    <a href="{{route('hrm.casual.manage')}}"
                    class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900">
                        <span>🧾</span> HRM Casual MGMT
                    </a>
                </li>
                <li>
                    <a href="#" 
                        class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900">
                            <span>📈</span>Reports
                    </a>
                </li>
            </ul>
        </div>


        {{-- ===== Section: Payments ===== --}}
        <div>
            <p class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Payments</p>

            {{-- M-Pesa --}}
            <button @click="paymentOpen = !paymentOpen"
                    :aria-expanded="paymentOpen"
                    class="group flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-slate-900">

                {{-- Wallet Icon --}}
                <svg class="h-5 w-5 text-slate-400 group-hover:text-slate-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2v-5zm0 0h-4a2 2 0 100 4h4"/>
                </svg>

                <span class="flex-1 text-left">M-Pesa</span>

                <svg :class="paymentOpen ? 'rotate-180' : ''"
                    class="h-4 w-4 text-slate-400 transition-transform"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <ul x-show="paymentOpen" x-collapse class="mt-1 space-y-0.5 pl-9">
                <li><a wire:navigate href="{{ route('dtc-payment') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900"><span>👥</span>DTC</a></li>
            </ul>
        </div>


        {{-- ===== Section: Administration ===== --}}
        <div>
            <p class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Administration</p>

            {{-- User Management (collapsible) --}}
            <button @click="userMgmtOpen = !userMgmtOpen"
                    :aria-expanded="userMgmtOpen"
                    class="group flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-slate-900">
                <svg class="h-5 w-5 text-slate-400 group-hover:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="flex-1 text-left">User Management</span>
                <svg :class="userMgmtOpen ? 'rotate-180' : ''" class="h-4 w-4 text-slate-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <ul x-show="userMgmtOpen" x-collapse class="mt-1 space-y-0.5 pl-9">
                <li><a wire:navigate href="{{ route('usermanagement') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900"><span>👥</span>Users</a></li>
                <li><a wire:navigate href="{{ route('rolemanagement') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900"><span>🛡️</span>Roles</a></li>
                <li><a wire:navigate href="{{ route('permissionmanagement') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900"><span>🔑</span>Permissions</a></li>
                <li><a wire:navigate href="{{ route('offboarding.index') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900"><span>🚪</span>Offboard User</a></li>
            </ul>

            {{-- Organization Setup --}}
            <button @click="orgSetupOpen = !orgSetupOpen"
                    :aria-expanded="orgSetupOpen"
                    class="group flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-slate-900">

                {{-- Building Office Icon --}}
                <svg class="h-5 w-5 text-slate-400 group-hover:text-slate-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 21h18M5 21V7a2 2 0 012-2h10a2 2 0 012 2v14M9 9h1m4 0h1M9 13h1m4 0h1M9 17h1m4 0h1"/>
                </svg>

                <span class="flex-1 text-left">Organization Setup</span>

                <svg :class="orgSetupOpen ? 'rotate-180' : ''"
                    class="h-4 w-4 text-slate-400 transition-transform"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <ul x-show="orgSetupOpen" x-collapse class="mt-1 space-y-0.5 pl-9">
                <li>
                    <a wire:navigate href="{{ route('departments') }}"
                    class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900">
                        <span>🏢</span>
                        Departments
                    </a>
                </li>

                <li>
                    <a wire:navigate href="{{ route('divisions') }}"
                    class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900">
                        <span>🧩</span>
                        Divisions
                    </a>
                </li>

                <li>
                    <a wire:navigate href="{{ route('designations') }}"
                    class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900">
                        <span>🎖️</span>
                        Designations
                    </a>
                </li>
            </ul>
            {{-- Onboarding --}}
            <a wire:navigate href="{{ route('onboarding') }}" class="group mt-1 flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-slate-900">
                <svg class="h-5 w-5 text-slate-400 group-hover:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Onboarding
            </a>
        </div>

        {{-- ===== Section: Account ===== --}}
        <div>
            <p class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Account</p>

            <a wire:navigate href="{{ route('edit-profile') }}" class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-slate-900">
                <svg class="h-5 w-5 text-slate-400 group-hover:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Edit Profile
            </a>

            <a href="#" class="group mt-1 flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-slate-900">
                <svg class="h-5 w-5 text-slate-400 group-hover:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                Change Password
            </a>
        </div>
    </nav>

    {{-- Footer --}}
    <div class="border-t border-slate-200 px-3 py-3">
        <div class="flex items-center gap-3 rounded-lg px-2 py-2 hover:bg-slate-100">
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-indigo-700 text-sm font-semibold text-white">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="flex min-w-0 flex-1 flex-col leading-tight">
                <span class="truncate text-sm font-medium text-slate-900">{{ auth()->user()->name ?? 'User' }}</span>
                <span class="truncate text-xs text-slate-500">{{ auth()->user()->email ?? '' }}</span>
            </div>
        </div>
    </div>
</aside>


</div>
