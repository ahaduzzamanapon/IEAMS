@extends('layouts.app')

@section('content')
<div class="space-y-10 max-w-6xl mx-auto">
    
    <!-- Title Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold tracking-tight text-white">Dashboard Overview</h2>
            <p class="text-sm text-slate-400 mt-1">Real-time statistics across all National Housing Authority modules.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('assets.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition shadow-lg shadow-indigo-600/20">
                + Register Asset
            </a>
        </div>
    </div>

    <!-- 1. Asset Management Module Overview -->
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-slate-355 flex items-center gap-2">
            <span>🖥️</span> Asset Management Module
        </h3>
        <!-- Row 1: Core Statuses -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Total Assets</div>
                <div class="text-3xl font-bold text-indigo-400 mt-2">{{ $totalAssets }}</div>
                <div class="text-[10px] text-slate-550 mt-1">All registered assets</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Active Assets</div>
                <div class="text-3xl font-bold text-sky-400 mt-2">{{ $activeAssets }}</div>
                <div class="text-[10px] text-slate-550 mt-1">Available, assigned & in maintenance</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Available Assets</div>
                <div class="text-3xl font-bold text-emerald-400 mt-2">{{ $availableAssets }}</div>
                <div class="text-[10px] text-slate-550 mt-1">Ready for allocation</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Assigned Assets</div>
                <div class="text-3xl font-bold text-amber-400 mt-2">{{ $assignedAssets }}</div>
                <div class="text-[10px] text-slate-550 mt-1">Allocated to custodians</div>
            </div>
        </div>

        <!-- Row 2: Maintenance & Disposal Statuses -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Under Maintenance</div>
                <div class="text-3xl font-bold text-rose-400 mt-2">{{ $underMaintenance }}</div>
                <div class="text-[10px] text-slate-550 mt-1">Repair and servicing active</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Scrap Assets</div>
                <div class="text-3xl font-bold text-orange-400 mt-2">{{ $scrapAssets }}</div>
                <div class="text-[10px] text-slate-550 mt-1">Permanently damaged items</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Disposed Assets</div>
                <div class="text-3xl font-bold text-red-400 mt-2">{{ $disposedAssets }}</div>
                <div class="text-[10px] text-slate-550 mt-1">Sold or scrapped actions done</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Under Warranty</div>
                <div class="text-3xl font-bold text-violet-400 mt-2">{{ $underWarranty }}</div>
                <div class="text-[10px] text-slate-550 mt-1">Active warranty coverage</div>
            </div>
        </div>

        <!-- Row 3: Warranty Expired & Valuations -->
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Warranty Expired</div>
                <div class="text-3xl font-bold text-amber-500 mt-2">{{ $warrantyExpired }}</div>
                <div class="text-[10px] text-slate-550 mt-1">Requires renewal checks</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Current Asset Value</div>
                <div class="text-3xl font-bold text-slate-200 mt-2">৳{{ number_format($currentAssetValue, 2) }}</div>
                <div class="text-[10px] text-slate-550 mt-1">Total current book value</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Monthly Depreciation</div>
                <div class="text-3xl font-bold text-teal-400 mt-2">৳{{ number_format($monthlyDepreciation, 2) }}</div>
                <div class="text-[10px] text-slate-550 mt-1">Applied this month</div>
            </div>
        </div>
    </div>

    <!-- 2. Property & Project Module Overview -->
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-slate-355 flex items-center gap-2">
            <span>🏗️</span> Property & Project Module
        </h3>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Total Projects</div>
                <div class="text-3xl font-bold text-blue-400 mt-2">{{ $totalProjects }}</div>
                <div class="text-[10px] text-slate-550 mt-1">{{ $activeProjects }} ongoing, {{ $completedProjects }} done</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Apartment Occupancy</div>
                <div class="text-3xl font-bold text-emerald-400 mt-2">{{ $rentedApartments + $soldApartments }}/{{ $totalApartments }}</div>
                <div class="text-[10px] text-slate-550 mt-1">{{ $vacantApartments }} vacant apartments remaining</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Sales & Lease Plots</div>
                <div class="text-3xl font-bold text-amber-400 mt-2">{{ $soldPlots }}/{{ $totalPlots }}</div>
                <div class="text-[10px] text-slate-550 mt-1">{{ $vacantPlots }} vacant plots available</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Financial Revenue</div>
                <div class="text-3xl font-bold text-teal-400 mt-2">৳{{ number_format($totalPlotSales + $totalApartmentSales, 2) }}</div>
                <div class="text-[10px] text-slate-550 mt-1">Total property sales value</div>
            </div>
        </div>
    </div>

    <!-- 3. Vehicle Management Module Overview -->
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-slate-355 flex items-center gap-2">
            <span>🚗</span> Vehicle Management Module
        </h3>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Total Fleet</div>
                <div class="text-3xl font-bold text-indigo-400 mt-2">{{ $totalVehicles }}</div>
                <div class="text-[10px] text-slate-550 mt-1">Vehicles in registration</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Available Vehicles</div>
                <div class="text-3xl font-bold text-emerald-400 mt-2">{{ $availableVehicles }}</div>
                <div class="text-[10px] text-slate-550 mt-1">Ready for allocation</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Active Drivers</div>
                <div class="text-3xl font-bold text-amber-400 mt-2">{{ $activeDrivers }}/{{ $totalDrivers }}</div>
                <div class="text-[10px] text-slate-550 mt-1">Drivers with active licenses</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Out of Service</div>
                <div class="text-3xl font-bold text-rose-400 mt-2">{{ $outOfServiceVehicles + $accidentVehicles }}</div>
                <div class="text-[10px] text-slate-550 mt-1">Maintenance & accidents logs</div>
            </div>
        </div>
    </div>

    <!-- 4. Module Dashboards Quick Links (At the Very Bottom) -->
    <div class="space-y-4 pt-6 border-t border-slate-800/60">
        <h3 class="text-lg font-bold text-white flex items-center gap-2">
            <span>🔗</span> Dedicated Dashboards Quick Links
        </h3>
        <p class="text-xs text-slate-400">Navigate to dedicated dashboard modules for specialized operational parameters.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-2">
            <!-- Asset Dashboard Quick Link -->
            <div class="p-5 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition flex flex-col justify-between h-48">
                <div class="flex items-start gap-4">
                    <span class="p-2 bg-indigo-500/10 text-indigo-400 rounded-xl text-lg">🖥️</span>
                    <div>
                        <h4 class="text-sm font-bold text-white">Asset Dashboard</h4>
                        <p class="text-[11px] text-slate-400 mt-1">View depreciation algorithms, salvage values, and warranty expiry logs.</p>
                    </div>
                </div>
                <a href="{{ route('assets.dashboard') }}" class="w-full py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs rounded-xl transition text-center mt-2 shadow-lg shadow-indigo-600/10">
                    Open Asset Dashboard
                </a>
            </div>

            <!-- Property Dashboard Quick Link -->
            <div class="p-5 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition flex flex-col justify-between h-48">
                <div class="flex items-start gap-4">
                    <span class="p-2 bg-emerald-500/10 text-emerald-400 rounded-xl text-lg">🏗️</span>
                    <div>
                        <h4 class="text-sm font-bold text-white">Property Dashboard</h4>
                        <p class="text-[11px] text-slate-400 mt-1">Track land registries, layout plot allocations, and sales revenue models.</p>
                    </div>
                </div>
                <a href="{{ route('property.dashboard') }}" class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl transition text-center mt-2 shadow-lg shadow-emerald-600/10">
                    Open Property Dashboard
                </a>
            </div>

            <!-- Vehicle Dashboard Quick Link -->
            <div class="p-5 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition flex flex-col justify-between h-48">
                <div class="flex items-start gap-4">
                    <span class="p-2 bg-amber-500/10 text-amber-400 rounded-xl text-lg">🚗</span>
                    <div>
                        <h4 class="text-sm font-bold text-white">Vehicle Dashboard</h4>
                        <p class="text-[11px] text-slate-400 mt-1">Monitor fleet statuses, active driver licensing, and accident logs.</p>
                    </div>
                </div>
                <a href="{{ route('vehicles.dashboard') }}" class="w-full py-2 bg-amber-600 hover:bg-amber-500 text-white font-semibold text-xs rounded-xl transition text-center mt-2 shadow-lg shadow-amber-600/10">
                    Open Vehicle Dashboard
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
