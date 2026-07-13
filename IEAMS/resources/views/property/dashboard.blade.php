@extends('layouts.app')

@section('content')
<div class="space-y-10 max-w-6xl mx-auto">
    
    <!-- Title Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">Property & Project Dashboard</h2>
            <p class="text-sm text-slate-400 mt-1">Real-time statistics for National Housing Authority projects, plots, buildings, and sales.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('property.projects') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition shadow-lg shadow-indigo-600/20">
                Go to Projects Registry
            </a>
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="space-y-6">
        <!-- Row 1: Project Overview -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-blue-500/10 hover:border-blue-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Total Projects</div>
                <div class="text-3xl font-bold text-blue-400 mt-2">{{ $totalProjects }}</div>
                <div class="text-[10px] text-slate-500 mt-1">NHA Housing Projects</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-emerald-500/10 hover:border-emerald-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Active Projects</div>
                <div class="text-3xl font-bold text-emerald-400 mt-2">{{ $activeProjects }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Ongoing construction & dev</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-amber-500/10 hover:border-amber-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Completed Projects</div>
                <div class="text-3xl font-bold text-amber-400 mt-2">{{ $completedProjects }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Successfully delivered</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-indigo-500/10 hover:border-indigo-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Total Land Area</div>
                <div class="text-3xl font-bold text-indigo-400 mt-2">{{ number_format($totalLandArea, 2) }} Acre</div>
                <div class="text-[10px] text-slate-500 mt-1">Acquired site area</div>
            </div>
        </div>

        <!-- Row 2: Property Unit Registry -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-violet-500/10 hover:border-violet-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Total Plots</div>
                <div class="text-3xl font-bold text-violet-400 mt-2">{{ $totalPlots }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Plots mapping database</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-sky-500/10 hover:border-sky-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Vacant Plots</div>
                <div class="text-3xl font-bold text-sky-400 mt-2">{{ $vacantPlots }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Available for sale / lease</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-emerald-500/10 hover:border-emerald-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Total Apartments</div>
                <div class="text-3xl font-bold text-emerald-400 mt-2">{{ $totalApartments }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Total housing units</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-amber-500/10 hover:border-amber-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Apartment Occupancy</div>
                <div class="text-3xl font-bold text-amber-400 mt-2">{{ $rentedApartments + $soldApartments }}/{{ $totalApartments }}</div>
                <div class="text-[10px] text-slate-500 mt-1">{{ $vacantApartments }} vacant apartments remaining</div>
            </div>
        </div>

        <!-- Row 3: Revenue & Construction -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-teal-500/10 hover:border-teal-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Plot Sales Value</div>
                <div class="text-3xl font-bold text-teal-400 mt-2">৳{{ number_format($totalPlotSales, 2) }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Revenue from plot sales</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-sky-500/10 hover:border-sky-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Apartment Sales Value</div>
                <div class="text-3xl font-bold text-sky-400 mt-2">৳{{ number_format($totalApartmentSales, 2) }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Revenue from apartment sales</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-emerald-500/10 hover:border-emerald-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Monthly Rental Income</div>
                <div class="text-3xl font-bold text-emerald-400 mt-2">৳{{ number_format($totalRentalIncome, 2) }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Rent collections</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-rose-500/10 hover:border-rose-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Under Construction</div>
                <div class="text-3xl font-bold text-rose-400 mt-2">{{ $underConstructionBuildings }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Ongoing building units</div>
            </div>
        </div>
    </div>

</div>
@endsection
