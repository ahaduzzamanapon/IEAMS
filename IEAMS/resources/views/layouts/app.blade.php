<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IEAMS - Integrated Estate & Asset Management System</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #020617;
        }
        ::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #334155;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full overflow-hidden flex flex-col md:flex-row bg-[#080B11]">
    
    <!-- Sidebar Navigation -->
    <aside class="w-full md:w-64 bg-[#0E131F] border-r border-slate-800/60 flex flex-col shrink-0">
        <div class="h-16 flex items-center px-6 border-b border-slate-200 gap-3">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-[#0e76bc] to-blue-500 flex items-center justify-center font-bold text-white shadow-md shadow-blue-500/20">
                M
            </div>
            <div>
                <h1 class="text-md font-bold tracking-tight text-[#0e76bc]">Mysoft IEAMS</h1>
                <p class="text-[10px] text-slate-500">NHA Estate & Asset System</p>
            </div>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-500/10 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                <span class="mr-3">📊</span> Dashboard
            </a>
            <div class="pt-4 pb-2 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Asset Module</div>
            <a href="{{ route('assets.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('assets.dashboard') ? 'bg-indigo-500/10 text-indigo-400 border-l-4 border-indigo-500 font-semibold' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                <span class="mr-3">📈</span> Asset Dashboard
            </a>
            <a href="{{ route('assets.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('assets.index', 'assets.create', 'assets.show') ? 'bg-indigo-500/10 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                <span class="mr-3">🖥️</span> Asset Register
            </a>
            <a href="{{ route('assets.reports') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('assets.reports') ? 'bg-indigo-500/10 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                <span class="mr-3">📝</span> Asset Reports
            </a>

            <div class="pt-4 pb-2 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Property Module</div>
            <a href="{{ route('property.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('property.dashboard') ? 'bg-indigo-500/10 text-indigo-400 border-l-4 border-indigo-500 font-semibold' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                <span class="mr-3">📈</span> Property Dashboard
            </a>
            <details class="group [&_summary::-webkit-details-marker]:hidden" {{ request()->routeIs('property.projects', 'property.project-show', 'property.lands.*', 'property.plots.*', 'property.buildings.*', 'property.floors.*', 'property.apartments.*') ? 'open' : '' }}>
                <summary class="flex items-center justify-between px-4 py-2.5 text-sm font-medium text-slate-400 rounded-xl hover:bg-slate-800/40 hover:text-slate-200 cursor-pointer transition-all duration-200 select-none">
                    <div class="flex items-center">
                        <span class="mr-3">🏗️</span>
                        <span>Property Registry</span>
                    </div>
                    <span class="shrink-0 transition duration-200 group-open:-rotate-180">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </summary>
                <div class="mt-1.5 space-y-1 pl-4 border-l border-slate-200 dark:border-slate-800/60 ml-4">
                    <a href="{{ route('property.projects') }}" class="flex items-center px-4 py-2 text-xs font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('property.projects', 'property.project-show') ? 'bg-indigo-500/10 text-indigo-650 dark:text-indigo-400 border-l-2 border-indigo-500 font-semibold' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/40 hover:text-slate-200' }}">
                        🏗️ Projects List
                    </a>
                    <a href="{{ route('property.lands.index') }}" class="flex items-center px-4 py-2 text-xs font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('property.lands.*') ? 'bg-indigo-500/10 text-indigo-650 dark:text-indigo-400 border-l-2 border-indigo-500 font-semibold' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/40 hover:text-slate-200' }}">
                        🗺️ Land Information
                    </a>
                    <a href="{{ route('property.plots.index') }}" class="flex items-center px-4 py-2 text-xs font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('property.plots.*') ? 'bg-indigo-500/10 text-indigo-650 dark:text-indigo-400 border-l-2 border-indigo-500 font-semibold' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/40 hover:text-slate-200' }}">
                        📌 Plot Management
                    </a>
                    <a href="{{ route('property.buildings.index') }}" class="flex items-center px-4 py-2 text-xs font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('property.buildings.*') ? 'bg-indigo-500/10 text-indigo-650 dark:text-indigo-400 border-l-2 border-indigo-500 font-semibold' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/40 hover:text-slate-200' }}">
                        🏢 Building Management
                    </a>
                    <a href="{{ route('property.floors.index') }}" class="flex items-center px-4 py-2 text-xs font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('property.floors.*') ? 'bg-indigo-500/10 text-indigo-650 dark:text-indigo-400 border-l-2 border-indigo-500 font-semibold' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/40 hover:text-slate-200' }}">
                        📶 Floor Management
                    </a>
                    <a href="{{ route('property.apartments.index') }}" class="flex items-center px-4 py-2 text-xs font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('property.apartments.*') ? 'bg-indigo-500/10 text-indigo-650 dark:text-indigo-400 border-l-2 border-indigo-500 font-semibold' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/40 hover:text-slate-200' }}">
                        🚪 Apartment Management
                    </a>
                </div>
            </details>
            <a href="{{ route('property.sales') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('property.sales') ? 'bg-indigo-500/10 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                <span class="mr-3">💰</span> Property Sales
            </a>
            <a href="{{ route('property.rents') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('property.rents') ? 'bg-indigo-500/10 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                <span class="mr-3">🔑</span> Rent Management
            </a>
            <a href="{{ route('property.reports') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('property.reports') ? 'bg-indigo-500/10 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                <span class="mr-3">📑</span> Property Reports
            </a>

            <div class="pt-4 pb-2 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Vehicle Module</div>
            <a href="{{ route('vehicles.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('vehicles.dashboard') ? 'bg-indigo-500/10 text-indigo-400 border-l-4 border-indigo-500 font-semibold' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                <span class="mr-3">📈</span> Vehicle Dashboard
            </a>
            <a href="{{ route('vehicles.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('vehicles.index', 'vehicles.create', 'vehicles.show') ? 'bg-indigo-500/10 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                <span class="mr-3">🚗</span> Vehicle Fleet
            </a>
            <a href="{{ route('vehicles.reports') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('vehicles.reports') ? 'bg-indigo-500/10 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                <span class="mr-3">📓</span> Vehicle Reports
            </a>

            <div class="pt-4 pb-2 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Setup Settings</div>
            <details class="group [&_summary::-webkit-details-marker]:hidden" {{ request()->routeIs('categories.*', 'vendors.*', 'offices.*', 'branches.*', 'vehicles.drivers', 'divisions.*', 'districts.*', 'upazilas.*', 'departments.*', 'designations.*') ? 'open' : '' }}>
                <summary class="flex items-center justify-between px-4 py-2.5 text-sm font-medium text-slate-400 rounded-xl hover:bg-slate-800/40 hover:text-slate-200 cursor-pointer transition-all duration-200 select-none">
                    <div class="flex items-center">
                        <span class="mr-3">⚙️</span>
                        <span>Global Setups</span>
                    </div>
                    <span class="shrink-0 transition duration-200 group-open:-rotate-180">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </summary>
                <div class="mt-1.5 space-y-1 pl-4 border-l border-slate-800 ml-4">
                    <a href="{{ route('categories.index') }}" class="flex items-center px-4 py-2 text-xs font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('categories.index') ? 'bg-indigo-500/10 text-indigo-400 border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                        📂 Category Setup
                    </a>
                    <a href="{{ route('vendors.index') }}" class="flex items-center px-4 py-2 text-xs font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('vendors.index') ? 'bg-indigo-500/10 text-indigo-400 border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                        🏭 Vendor Setup
                    </a>
                    <a href="{{ route('vehicles.drivers') }}" class="flex items-center px-4 py-2 text-xs font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('vehicles.drivers') ? 'bg-indigo-500/10 text-indigo-400 border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                        👨🏻‍✈️ Driver Setup
                    </a>
                    <a href="{{ route('offices.index') }}" class="flex items-center px-4 py-2 text-xs font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('offices.*') ? 'bg-indigo-500/10 text-indigo-400 border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                        🏢 Office Setup
                    </a>
                    <a href="{{ route('branches.index') }}" class="flex items-center px-4 py-2 text-xs font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('branches.*') ? 'bg-indigo-500/10 text-indigo-400 border-l-2 border-indigo-500 font-semibold' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                        🌿 Branch Setup
                    </a>
                    <a href="{{ route('departments.index') }}" class="flex items-center px-4 py-2 text-xs font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('departments.*') ? 'bg-indigo-500/10 text-indigo-400 border-l-2 border-indigo-500 font-semibold' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                        🏢 Department Setup
                    </a>
                    <a href="{{ route('designations.index') }}" class="flex items-center px-4 py-2 text-xs font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('designations.*') ? 'bg-indigo-500/10 text-indigo-400 border-l-2 border-indigo-500 font-semibold' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                        🏷️ Designation Setup
                    </a>
                    <a href="{{ route('divisions.index') }}" class="flex items-center px-4 py-2 text-xs font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('divisions.*') ? 'bg-indigo-500/10 text-indigo-400 border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                        🗺️ Division Setup
                    </a>
                    <a href="{{ route('districts.index') }}" class="flex items-center px-4 py-2 text-xs font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('districts.*') ? 'bg-indigo-500/10 text-indigo-400 border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                        📍 District Setup
                    </a>
                    <a href="{{ route('upazilas.index') }}" class="flex items-center px-4 py-2 text-xs font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('upazilas.*') ? 'bg-indigo-500/10 text-indigo-400 border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                        📶 Upazila Setup
                    </a>
                </div>
            </details>

            <div class="pt-4 pb-2 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Security & Settings</div>
            <a href="{{ route('users.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('users.index', 'users.create', 'users.edit') ? 'bg-indigo-500/10 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                <span class="mr-3">👤</span> User Accounts
            </a>
            <a href="{{ route('rbac.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('rbac.index') ? 'bg-indigo-500/10 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                <span class="mr-3">🛡️</span> Roles & Permissions
            </a>
            <a href="{{ route('notifications.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('notifications.*') ? 'bg-indigo-500/10 text-indigo-400 border-l-4 border-indigo-500 font-semibold' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                <span class="mr-3">🔔</span> System Notifications
            </a>
            <div class="px-4 py-2">
                <form action="{{ route('system.clear-cache') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-[#0e76bc] bg-slate-100 hover:bg-blue-50/50 border border-slate-200 transition duration-200 cursor-pointer">
                        <span class="mr-2">⚡</span> System Optimize
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <!-- Main Content Container -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Top Navbar -->
        <header class="h-16 bg-[#0B0F19] border-b border-slate-800/60 flex items-center justify-between px-8 shrink-0">
            <div class="text-sm font-medium text-slate-400">
                National Housing Authority System
            </div>
            
            <div class="flex items-center gap-4">
                <!-- Notification Bell Dropdown -->
                <div class="relative mr-2" id="notificationDropdown">
                    <button type="button" onclick="toggleNotifMenu()" class="relative p-1.5 text-slate-400 hover:text-slate-200 hover:bg-slate-800/40 rounded-xl transition cursor-pointer">
                        <span class="text-base">🔔</span>
                        <span id="notifBadge" class="absolute -top-1 -right-1 hidden items-center justify-center min-w-4 h-4 px-1 text-[8px] font-bold text-white bg-indigo-600 rounded-full">0</span>
                    </button>
                    <!-- Dropdown Panel -->
                    <div id="notifPanel" class="absolute right-0 mt-2 w-80 bg-[#FFFFFF] border border-slate-200 rounded-2xl shadow-2xl overflow-hidden hidden z-50 text-slate-800">
                        <div class="p-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Alerts & Notifications</span>
                            <a href="{{ route('notifications.index') }}" class="text-[10px] text-indigo-600 font-semibold hover:underline">View Log</a>
                        </div>
                        <div id="notifList" class="max-h-60 overflow-y-auto divide-y divide-slate-100 text-xs">
                            <div class="p-4 text-center text-slate-400 italic">Loading alerts...</div>
                        </div>
                    </div>
                </div>

                <div class="text-xs font-semibold text-slate-500 mr-2">
                    Logged in as {{ Auth::user() ? Auth::user()->name : 'Guest' }}
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline m-0">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-500 text-white font-medium text-xs rounded-lg transition cursor-pointer">
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- Main View Space -->
        <main class="flex-1 overflow-y-auto p-8">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm flex items-center shadow-lg shadow-emerald-950/20">
                    <span class="mr-2">✓</span> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm flex items-center shadow-lg shadow-rose-950/20">
                    <span class="mr-2">⚠</span> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        async function loadUnreadNotifications() {
            try {
                const res = await fetch('/api/notifications/unread');
                if (!res.ok) return;
                const data = await res.json();
                const badge = document.getElementById('notifBadge');
                const list = document.getElementById('notifList');
                
                if (data.length > 0) {
                    badge.textContent = data.length;
                    badge.classList.remove('hidden');
                    badge.classList.add('flex');
                    
                    list.innerHTML = '';
                    data.forEach(notif => {
                        const row = document.createElement('div');
                        row.className = 'p-3 hover:bg-slate-50 transition flex flex-col gap-1 border-b border-slate-100 last:border-0';
                        
                        const titleLine = document.createElement('div');
                        titleLine.className = 'font-bold text-slate-800 flex items-center justify-between';
                        titleLine.innerHTML = `<span>${notif.title}</span><button onclick="readDropdownNotif(event, ${notif.id})" class="text-xs text-emerald-600 hover:text-emerald-500 font-bold p-0.5 transition cursor-pointer">✓</button>`;
                        
                        const msgLine = document.createElement('div');
                        msgLine.className = 'text-[11px] text-slate-550 leading-relaxed';
                        msgLine.textContent = notif.message;
                        
                        row.appendChild(titleLine);
                        row.appendChild(msgLine);
                        list.appendChild(row);
                    });
                } else {
                    badge.classList.add('hidden');
                    badge.classList.remove('flex');
                    list.innerHTML = '<div class="p-4 text-center text-slate-400 italic">No new notifications.</div>';
                }
            } catch (err) {
                console.error('Error fetching unread notifications:', err);
            }
        }

        function toggleNotifMenu() {
            const panel = document.getElementById('notifPanel');
            panel.classList.toggle('hidden');
        }

        async function readDropdownNotif(event, id) {
            event.stopPropagation();
            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const res = await fetch(`/api/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    }
                });
                const data = await res.json();
                if (data.success) {
                    loadUnreadNotifications();
                    // If we are currently on the notifications page, reload to show read status
                    if (window.location.pathname === '/notifications') {
                        window.location.reload();
                    }
                }
            } catch (err) {
                console.error('Error marking notification read:', err);
            }
        }

        // Close panel when clicking outside
        document.addEventListener('click', function(e) {
            const panel = document.getElementById('notifPanel');
            const trigger = document.getElementById('notificationDropdown');
            if (panel && trigger && !trigger.contains(e.target)) {
                panel.classList.add('hidden');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            loadUnreadNotifications();
        });
    </script>
</body>
</html>
