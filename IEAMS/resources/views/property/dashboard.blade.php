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
        <div>
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4 px-1">Project Overview</h3>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                    <div class="text-xs font-semibold text-slate-500 uppercase">Total Projects</div>
                    <div class="text-3xl font-bold text-blue-400 mt-2">{{ $totalProjects }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">NHA Housing Projects</div>
                </div>
                <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                    <div class="text-xs font-semibold text-slate-500 uppercase">Active Projects</div>
                    <div class="text-3xl font-bold text-emerald-400 mt-2">{{ $activeProjects }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">Ongoing construction & dev</div>
                </div>
                <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                    <div class="text-xs font-semibold text-slate-500 uppercase">Completed Projects</div>
                    <div class="text-3xl font-bold text-amber-400 mt-2">{{ $completedProjects }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">Successfully delivered</div>
                </div>
                <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                    <div class="text-xs font-semibold text-slate-500 uppercase">Total Land Area</div>
                    <div class="text-3xl font-bold text-indigo-400 mt-2">{{ number_format($totalLandArea, 2) }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">Acres acquired</div>
                </div>
            </div>
        </div>

        <!-- Row 2: Plot & Building -->
        <div>
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4 px-1">Plot & Building Status</h3>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                    <div class="text-xs font-semibold text-slate-500 uppercase">Total Plots</div>
                    <div class="text-3xl font-bold text-violet-400 mt-2">{{ $totalPlots }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">Plot mapping database</div>
                </div>
                <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                    <div class="text-xs font-semibold text-slate-500 uppercase">Vacant Plots</div>
                    <div class="text-3xl font-bold text-sky-400 mt-2">{{ $vacantPlots }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">Available for sale / lease</div>
                </div>
                <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                    <div class="text-xs font-semibold text-slate-500 uppercase">Sold Plots</div>
                    <div class="text-3xl font-bold text-emerald-400 mt-2">{{ $soldPlots }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">Plots sold / transferred</div>
                </div>
                <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                    <div class="text-xs font-semibold text-slate-500 uppercase">Under Construction</div>
                    <div class="text-3xl font-bold text-amber-400 mt-2">{{ $underConstructionPlots }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">Plots with active buildings</div>
                </div>
            </div>
        </div>

        <!-- Row 3: Apartment Status -->
        <div>
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4 px-1">Apartment Status</h3>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                    <div class="text-xs font-semibold text-slate-500 uppercase">Total Apartments</div>
                    <div class="text-3xl font-bold text-white mt-2">{{ $totalApartments }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">Total housing units</div>
                </div>
                <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                    <div class="text-xs font-semibold text-slate-500 uppercase">Vacant Apartments</div>
                    <div class="text-3xl font-bold text-emerald-400 mt-2">{{ $vacantApartments }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">Ready for sale / rent</div>
                </div>
                <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                    <div class="text-xs font-semibold text-slate-500 uppercase">Sold Apartments</div>
                    <div class="text-3xl font-bold text-indigo-400 mt-2">{{ $soldApartments }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">Transferred to buyers</div>
                </div>
                <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                    <div class="text-xs font-semibold text-slate-500 uppercase">Rented Apartments</div>
                    <div class="text-3xl font-bold text-teal-400 mt-2">{{ $rentedApartments }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">Currently tenanted</div>
                </div>
            </div>
        </div>

        <!-- Row 4: Rent & Revenue (SRS Dashboard Requirements) -->
        <div>
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4 px-1">Rental & Financial Summary</h3>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                    <div class="text-xs font-semibold text-slate-500 uppercase">Active Rent</div>
                    <div class="text-3xl font-bold text-sky-400 mt-2">{{ $activeRents }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">Active rent agreements</div>
                </div>
                <div class="p-6 rounded-2xl bg-[#0E1325]/80 border {{ $rentExpiringSoon > 0 ? 'border-amber-500/40' : 'border-slate-800/60' }} hover:border-slate-700/60 transition shadow-lg">
                    <div class="text-xs font-semibold text-slate-500 uppercase">Rent Expiring Soon</div>
                    <div class="text-3xl font-bold {{ $rentExpiringSoon > 0 ? 'text-amber-400' : 'text-slate-400' }} mt-2">{{ $rentExpiringSoon }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">Agreements expiring in 30 days</div>
                    @if($rentExpiringSoon > 0)
                        <div class="text-[9px] font-bold text-amber-400 mt-1 uppercase">⚠ Action Required</div>
                    @endif
                </div>
                <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                    <div class="text-xs font-semibold text-slate-500 uppercase">Total Rental Income</div>
                    <div class="text-2xl font-bold text-teal-400 mt-2">৳{{ number_format($totalRentalIncome, 0) }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">Monthly rent collections</div>
                </div>
                <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                    <div class="text-xs font-semibold text-slate-500 uppercase">Total Sale Value</div>
                    <div class="text-2xl font-bold text-emerald-400 mt-2">৳{{ number_format($totalSaleValue, 0) }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">Plot + Apartment sales</div>
                </div>
            </div>
        </div>

        <!-- Row 5: Building Summary -->
        <div>
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4 px-1">Building Summary</h3>
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                    <div class="text-xs font-semibold text-slate-500 uppercase">Total Buildings</div>
                    <div class="text-3xl font-bold text-white mt-2">{{ $totalBuildings }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">All registered buildings</div>
                </div>
                <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                    <div class="text-xs font-semibold text-slate-500 uppercase">Under Construction</div>
                    <div class="text-3xl font-bold text-rose-400 mt-2">{{ $underConstructionBuildings }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">Ongoing building units</div>
                </div>
                <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/60 hover:border-slate-700/60 transition shadow-lg">
                    <div class="text-xs font-semibold text-slate-500 uppercase">Completed Buildings</div>
                    <div class="text-3xl font-bold text-emerald-400 mt-2">{{ $completedBuildings }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">Ready for occupancy</div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
