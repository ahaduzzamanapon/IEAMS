@extends('layouts.app')

@section('content')
<div class="space-y-10 max-w-6xl mx-auto">
    
    <!-- Title Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">Asset Dashboard</h2>
            <p class="text-sm text-slate-400 mt-1">Real-time statistics and financials for NHA Asset Registry.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('assets.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition shadow-lg shadow-indigo-600/20">
                + Register Asset
            </a>
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="space-y-6">
        <!-- Row 1: Core Statuses -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-indigo-500/10 hover:border-indigo-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Total Assets</div>
                <div class="text-3xl font-bold text-indigo-400 mt-2">{{ $totalAssets }}</div>
                <div class="text-[10px] text-slate-500 mt-1">All registered assets</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-sky-500/10 hover:border-sky-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Active Assets</div>
                <div class="text-3xl font-bold text-sky-400 mt-2">{{ $activeAssets }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Available, assigned & in maintenance</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-emerald-500/10 hover:border-emerald-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Available Assets</div>
                <div class="text-3xl font-bold text-emerald-400 mt-2">{{ $availableAssets }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Ready for allocation</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-amber-500/10 hover:border-amber-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Assigned Assets</div>
                <div class="text-3xl font-bold text-amber-400 mt-2">{{ $assignedAssets }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Allocated to custodians</div>
            </div>
        </div>

        <!-- Row 2: Maintenance & Disposal Statuses -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-rose-500/10 hover:border-rose-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Under Maintenance</div>
                <div class="text-3xl font-bold text-rose-400 mt-2">{{ $underMaintenance }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Repair and servicing active</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-orange-500/10 hover:border-orange-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Scrap Assets</div>
                <div class="text-3xl font-bold text-orange-400 mt-2">{{ $scrapAssets }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Permanently damaged items</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-red-500/10 hover:border-red-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Disposed Assets</div>
                <div class="text-3xl font-bold text-red-400 mt-2">{{ $disposedAssets }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Sold or scrapped actions done</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-violet-500/10 hover:border-violet-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Under Warranty</div>
                <div class="text-3xl font-bold text-violet-400 mt-2">{{ $underWarranty }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Active warranty coverage</div>
            </div>
        </div>

        <!-- Row 3: Warranty Expired & Valuations -->
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-amber-600/10 hover:border-amber-600/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Warranty Expired</div>
                <div class="text-3xl font-bold text-amber-500 mt-2">{{ $warrantyExpired }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Requires renewal checks</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-500/10 hover:border-slate-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Current Asset Value</div>
                <div class="text-3xl font-bold text-slate-200 mt-2">৳{{ number_format($currentAssetValue, 2) }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Total current book value</div>
            </div>
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-teal-500/10 hover:border-teal-500/30 transition shadow-lg">
                <div class="text-xs font-semibold text-slate-500 uppercase">Monthly Depreciation</div>
                <div class="text-3xl font-bold text-teal-400 mt-2">৳{{ number_format($monthlyDepreciation, 2) }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Applied this month</div>
            </div>
        </div>
    </div>

</div>
@endsection
