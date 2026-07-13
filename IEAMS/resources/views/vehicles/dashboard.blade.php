@extends('layouts.app')

@section('content')
<div class="space-y-10 max-w-6xl mx-auto">
    
    <!-- Title Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">Vehicle Fleet Dashboard</h2>
            <p class="text-sm text-slate-400 mt-1">Real-time vehicle registry, driver roster, and document expiry monitoring.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('vehicles.create') }}" class="px-4 py-2 bg-slate-900 border border-slate-700 hover:bg-slate-800 text-slate-300 font-medium text-sm rounded-xl transition">
                + Register Vehicle
            </a>
            <a href="{{ route('vehicles.index') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition shadow-lg shadow-indigo-600/20">
                Go to Fleet Register
            </a>
        </div>
    </div>

    <!-- Row 1: Fleet Status Metrics -->
    <div class="space-y-4">
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest px-1">Fleet Status Overview</h3>
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
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-rose-500/10 hover:border-rose-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Under Maintenance</div>
                <div class="text-3xl font-bold text-rose-400 mt-2">{{ $underMaintenanceVehicles }}</div>
                <div class="text-[10px] text-slate-500 mt-1">In service workshop</div>
            </div>
        </div>
    </div>

    <!-- Row 2: Fleet State + Drivers -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-teal-500/10 hover:border-teal-500/30 transition shadow-lg">
            <div class="text-xs font-semibold text-slate-500 uppercase">Total Drivers</div>
            <div class="text-3xl font-bold text-teal-400 mt-2">{{ $totalDrivers }}</div>
            <div class="text-[10px] text-slate-500 mt-1">Registered operators</div>
        </div>
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-sky-500/10 hover:border-sky-500/30 transition shadow-lg">
            <div class="text-xs font-semibold text-slate-500 uppercase">Active Drivers</div>
            <div class="text-3xl font-bold text-sky-400 mt-2">{{ $activeDrivers }}</div>
            <div class="text-[10px] text-slate-500 mt-1">Currently active</div>
        </div>
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-orange-500/10 hover:border-orange-500/30 transition shadow-lg">
            <div class="text-xs font-semibold text-slate-500 uppercase">Accident Flagged</div>
            <div class="text-3xl font-bold text-orange-400 mt-2">{{ $accidentVehicles }}</div>
            <div class="text-[10px] text-slate-500 mt-1">Vehicles in accident</div>
        </div>
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-red-500/10 hover:border-red-500/30 transition shadow-lg">
            <div class="text-xs font-semibold text-slate-500 uppercase">Out of Service</div>
            <div class="text-3xl font-bold text-red-400 mt-2">{{ $outOfServiceVehicles }}</div>
            <div class="text-[10px] text-slate-500 mt-1">Decommissioned</div>
        </div>
    </div>

    <!-- Row 3: Document Expiry Alerts (SRS 4.2.8 & BR-06/07/35) -->
    <div class="space-y-4">
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest px-1 flex items-center gap-2">
            <span class="text-amber-400">⚠</span> Document Expiry Monitoring (Next 30 Days)
        </h3>
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="p-5 rounded-2xl bg-[#0E1325]/80 border {{ $licenseExpiring > 0 ? 'border-amber-500/50' : 'border-slate-800/60' }} transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">License Expiring</div>
                <div class="text-3xl font-bold {{ $licenseExpiring > 0 ? 'text-amber-400' : 'text-slate-400' }} mt-2">{{ $licenseExpiring }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Driver licenses due soon</div>
                @if($licenseExpiring > 0)
                    <div class="mt-2 text-[9px] font-bold text-amber-400 uppercase tracking-wide">⚠ Action Required</div>
                @endif
            </div>
            <div class="p-5 rounded-2xl bg-[#0E1325]/80 border {{ $licenseExpired > 0 ? 'border-rose-500/60' : 'border-slate-800/60' }} transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">License Expired</div>
                <div class="text-3xl font-bold {{ $licenseExpired > 0 ? 'text-rose-400' : 'text-slate-400' }} mt-2">{{ $licenseExpired }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Drivers with expired license</div>
                @if($licenseExpired > 0)
                    <div class="mt-2 text-[9px] font-bold text-rose-400 uppercase tracking-wide">🚫 Blocked</div>
                @endif
            </div>
            <div class="p-5 rounded-2xl bg-[#0E1325]/80 border {{ $fitnessExpiring > 0 ? 'border-amber-500/50' : 'border-slate-800/60' }} transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Fitness Expiring</div>
                <div class="text-3xl font-bold {{ $fitnessExpiring > 0 ? 'text-amber-400' : 'text-slate-400' }} mt-2">{{ $fitnessExpiring }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Fitness certs due in 30d</div>
                @if($fitnessExpiring > 0)
                    <div class="mt-2 text-[9px] font-bold text-amber-400 uppercase tracking-wide">⚠ Renew Soon</div>
                @endif
            </div>
            <div class="p-5 rounded-2xl bg-[#0E1325]/80 border {{ $taxTokenExpiring > 0 ? 'border-amber-500/50' : 'border-slate-800/60' }} transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Tax Token Expiring</div>
                <div class="text-3xl font-bold {{ $taxTokenExpiring > 0 ? 'text-amber-400' : 'text-slate-400' }} mt-2">{{ $taxTokenExpiring }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Tax tokens due in 30d</div>
                @if($taxTokenExpiring > 0)
                    <div class="mt-2 text-[9px] font-bold text-amber-400 uppercase tracking-wide">⚠ Renew Soon</div>
                @endif
            </div>
            <div class="p-5 rounded-2xl bg-[#0E1325]/80 border {{ $insuranceExpiring > 0 ? 'border-amber-500/50' : 'border-slate-800/60' }} transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Insurance Expiring</div>
                <div class="text-3xl font-bold {{ $insuranceExpiring > 0 ? 'text-amber-400' : 'text-slate-400' }} mt-2">{{ $insuranceExpiring }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Insurance due in 30d</div>
                @if($insuranceExpiring > 0)
                    <div class="mt-2 text-[9px] font-bold text-amber-400 uppercase tracking-wide">⚠ Renew Soon</div>
                @endif
            </div>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="p-5 rounded-2xl bg-[#0E1325]/80 border {{ $registrationExpiring > 0 ? 'border-amber-500/50' : 'border-slate-800/60' }} transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Registration Expiring</div>
                <div class="text-3xl font-bold {{ $registrationExpiring > 0 ? 'text-amber-400' : 'text-slate-400' }} mt-2">{{ $registrationExpiring }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Registrations due in 30d</div>
                @if($registrationExpiring > 0)
                    <div class="mt-2 text-[9px] font-bold text-amber-400 uppercase tracking-wide">⚠ Renew Soon</div>
                @endif
            </div>
            <a href="{{ route('vehicles.index') }}?status=under_maintenance" class="p-5 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-indigo-500/30 transition shadow-lg cursor-pointer group">
                <div class="text-xs font-semibold text-slate-500 uppercase group-hover:text-slate-300 transition">Quick Links</div>
                <div class="text-sm font-semibold text-slate-300 mt-3 group-hover:text-indigo-400 transition">🔧 Maintenance Queue →</div>
                <div class="text-[10px] text-slate-500 mt-1">View vehicles under maintenance</div>
            </a>
            <a href="{{ route('vehicles.drivers') }}" class="p-5 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-teal-500/30 transition shadow-lg cursor-pointer group">
                <div class="text-xs font-semibold text-slate-500 uppercase group-hover:text-slate-300 transition">Driver Management</div>
                <div class="text-sm font-semibold text-slate-300 mt-3 group-hover:text-teal-400 transition">👨‍✈️ Driver Registry →</div>
                <div class="text-[10px] text-slate-500 mt-1">Manage and register drivers</div>
            </a>
        </div>
    </div>

</div>
@endsection
