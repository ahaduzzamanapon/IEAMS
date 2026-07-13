@extends('layouts.app')

@section('content')
<div class="space-y-10 max-w-6xl mx-auto">
    
    <!-- Title Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">Vehicle Fleet Dashboard</h2>
            <p class="text-sm text-slate-400 mt-1">Real-time vehicle registry status, driver rosters, and maintenance details.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('vehicles.index') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition shadow-lg shadow-indigo-600/20">
                Go to Fleet Register
            </a>
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="space-y-6">
        <!-- Row 1: Fleet Statuses -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-indigo-500/10 hover:border-indigo-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Total Fleet Size</div>
                <div class="text-3xl font-bold text-indigo-400 mt-2">{{ $totalVehicles }}</div>
                <div class="text-[10px] text-slate-500 mt-1">All registered vehicles</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-emerald-500/10 hover:border-emerald-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Available Vehicles</div>
                <div class="text-3xl font-bold text-emerald-400 mt-2">{{ $availableVehicles }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Ready for assignment</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-amber-500/10 hover:border-amber-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Assigned Vehicles</div>
                <div class="text-3xl font-bold text-amber-400 mt-2">{{ $assignedVehicles }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Currently in use</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-teal-500/10 hover:border-teal-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Total Drivers</div>
                <div class="text-3xl font-bold text-teal-400 mt-2">{{ $totalDrivers }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Registered operators</div>
            </div>
        </div>

        <!-- Row 2: Roster & Maintenance Details -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-sky-500/10 hover:border-sky-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Active Drivers</div>
                <div class="text-3xl font-bold text-sky-400 mt-2">{{ $activeDrivers }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Operators currently active</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-rose-500/10 hover:border-rose-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Under Maintenance</div>
                <div class="text-3xl font-bold text-rose-400 mt-2">{{ $underMaintenanceVehicles }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Vehicles in service workshop</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-orange-500/10 hover:border-orange-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Accident History</div>
                <div class="text-3xl font-bold text-orange-400 mt-2">{{ $accidentVehicles }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Vehicles flagged in accidents</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-red-500/10 hover:border-red-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Out of Service</div>
                <div class="text-3xl font-bold text-red-400 mt-2">{{ $outOfServiceVehicles }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Decommissioned vehicles</div>
            </div>
        </div>
    </div>

</div>
@endsection
