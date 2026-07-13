@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">
    
    <!-- Top Nav -->
    <div class="flex items-center justify-between">
        <a href="{{ route('property.projects') }}" class="text-sm font-medium text-slate-400 hover:text-slate-200 transition">
            ← Back to Project Registry
        </a>
        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-400">
            {{ ucfirst($project->status) }}
        </span>
    </div>

    <!-- Header Section -->
    <div class="border-b border-slate-800/60 pb-6">
        <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">NHA SITE SPECIFICATION</span>
        <h2 class="text-3xl font-extrabold text-white mt-1">{{ $project->name }}</h2>
        <p class="text-slate-400 text-sm mt-1">Location: {{ $project->upazila }}, {{ $project->district }}, {{ $project->division }} (Mouza: {{ $project->mouza }})</p>
    </div>

    <!-- Detailed Specification Logs (Full Width Stack) -->
    <div class="space-y-8">
        
        <!-- 1. Land Specifications -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-6">
            <div class="flex justify-between items-center border-b border-slate-850 pb-2">
                <h3 class="text-md font-bold text-white uppercase tracking-wider">Land Registry Logs</h3>
                <span class="text-xs text-slate-400">Total Project Land: {{ $project->total_land }} Acres</span>
            </div>
            
            <div class="space-y-4">
                @forelse($project->lands as $land)
                    <div class="p-4 bg-[#080B11] rounded-xl border border-slate-800/60 text-xs grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <span class="text-slate-500 block">Khatian/Dag</span>
                            <span class="text-white font-semibold block mt-0.5">{{ $land->khatian_number }} / {{ $land->dag_number }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block">Deed & Reg. Date</span>
                            <span class="text-slate-300 block mt-0.5">{{ $land->deed_number }} ({{ $land->registration_date->format('d M, Y') }})</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block">Land Amount</span>
                            <span class="text-white font-bold block mt-0.5">{{ $land->land_amount }} Acres</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block">Purchase Value</span>
                            <span class="text-emerald-400 font-bold block mt-0.5">৳{{ number_format($land->purchase_value, 2) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-slate-550 italic text-xs">No land deeds logged for this project.</div>
                @endforelse
            </div>
        </div>

        <!-- 2. Plots Layout -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-6">
            <div class="flex justify-between items-center border-b border-slate-850 pb-2">
                <h3 class="text-md font-bold text-white uppercase tracking-wider">Plots & Layout List</h3>
                <span class="text-xs text-slate-400">Planned Plots: {{ $project->total_planned_plot }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @forelse($project->plots as $plot)
                    <div class="p-4 bg-[#080B11] rounded-xl border border-slate-800/60 text-xs flex justify-between items-center">
                        <div>
                            <div class="font-semibold text-white">Plot No: {{ $plot->plot_number }}</div>
                            <div class="text-slate-500 mt-0.5">Area: {{ $plot->plot_area }} Sq.Ft.</div>
                        </div>
                        <div class="text-right">
                            <span class="px-2.5 py-1 text-[9px] font-bold rounded-full {{ $plot->status === 'vacant' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-indigo-500/10 text-indigo-400' }}">
                                {{ strtoupper($plot->status) }}
                            </span>
                            @if($plot->buildings->isNotEmpty())
                                <div class="text-[9px] text-slate-500 mt-2">{{ $plot->buildings->count() }} Buildings</div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-slate-550 italic text-xs col-span-3">No plots added to this project yet.</div>
                @endforelse
            </div>
        </div>

        <!-- 3. Buildings, Floors, Apartments structure -->
        @if($project->plots->isNotEmpty())
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-6">
                <h3 class="text-md font-bold text-white uppercase tracking-wider border-b border-slate-850 pb-2">Buildings & Apartments structure</h3>
                
                <div class="space-y-6">
                    @foreach($project->plots as $plot)
                        @foreach($plot->buildings as $building)
                            <div class="p-4 bg-[#080B11] rounded-xl border border-slate-800/60 space-y-4">
                                <div class="flex justify-between items-center border-b border-slate-800 pb-2">
                                    <div>
                                        <span class="text-[10px] text-indigo-400 font-bold">PLOT {{ $plot->plot_number }} BUILDING</span>
                                        <h4 class="text-sm font-bold text-white mt-0.5">{{ $building->name }} (No: {{ $building->number }})</h4>
                                    </div>
                                    <span class="text-xs text-slate-500">{{ $building->total_floor }} Floors | Status: {{ ucfirst($building->construction_status) }}</span>
                                </div>

                                <!-- Floors List -->
                                <div class="space-y-4">
                                    @forelse($building->floors as $floor)
                                        <div class="p-3 bg-[#0B0F19] rounded-lg border border-slate-850 space-y-3">
                                            <div class="flex justify-between items-center text-xs font-semibold text-slate-300">
                                                <span>Floor No: {{ $floor->floor_number }}</span>
                                                <span class="text-slate-500">Max Apartments: {{ $floor->total_apartment }}</span>
                                            </div>

                                            <!-- Apartments List -->
                                            <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                                                @forelse($floor->apartments as $apt)
                                                    <div class="p-2 bg-[#0E1325] rounded-md border border-slate-850 text-[10px] flex justify-between items-center">
                                                        <div>
                                                            <span class="font-bold text-white">No: {{ $apt->apartment_number }}</span>
                                                            <span class="text-slate-500 block">Size: {{ $apt->size }} Sq.Ft</span>
                                                        </div>
                                                        <span class="px-2 py-0.5 rounded-full font-bold {{ $apt->status === 'vacant' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-indigo-500/10 text-indigo-400' }}">
                                                            {{ strtoupper($apt->status) }}
                                                        </span>
                                                    </div>
                                                @empty
                                                    <div class="text-slate-650 italic text-[10px]">No apartments listed on this floor.</div>
                                                @endforelse
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-slate-650 italic text-xs">No floors structural data registered.</div>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
