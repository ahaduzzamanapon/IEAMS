@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    
    <!-- Title Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold tracking-tight text-white">Property & Estate Reports</h2>
            <p class="text-sm text-slate-400 mt-1">Audit land details, project progress, property sales, and rental agreements.</p>
        </div>
        <div>
            <a href="{{ route('property.dashboard') }}" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 hover:text-white font-medium text-sm rounded-xl transition">
                ← Property Dashboard
            </a>
        </div>
    </div>

    <!-- Filter Form Bar -->
    <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-xl">
        <form method="GET" action="{{ route('property.reports') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5">Sales From</label>
                <input type="date" name="sale_start" value="{{ request('sale_start') }}" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5">Sales To</label>
                <input type="date" name="sale_end" value="{{ request('sale_end') }}" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5">Rent From</label>
                <input type="date" name="rent_start" value="{{ request('rent_start') }}" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5">Rent To</label>
                <input type="date" name="rent_end" value="{{ request('rent_end') }}" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-xl transition shadow-lg shadow-indigo-600/20">
                    Apply Filter
                </button>
                <a href="{{ route('property.reports') }}" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-xl transition text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- 1. Project Site Status List -->
    <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-xl">
        <h3 class="text-lg font-bold text-white mb-6">Housing Site Occupancy Report</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 text-xs uppercase font-medium">
                    <tr>
                        <th class="px-6 py-4">Project ID & Name</th>
                        <th class="px-6 py-4">Total Land</th>
                        <th class="px-6 py-4">Total Plots</th>
                        <th class="px-6 py-4">Occupied Plots</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($projects as $p)
                        <tr class="hover:bg-slate-800/20">
                            <td class="px-6 py-4 font-semibold text-white">
                                {{ $p->name }}
                            </td>
                            <td class="px-6 py-4 text-xs">
                                {{ $p->total_land }} Acres
                            </td>
                            <td class="px-6 py-4 text-xs">
                                {{ $p->total_planned_plot }} Plots
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-emerald-400">
                                {{ $p->occupied_plots_count }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-indigo-500/10 text-indigo-400">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">No project sites found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- 2. Sales Report Panel -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-xl space-y-6">
            <div class="flex justify-between items-center border-b border-slate-850 pb-2">
                <h3 class="text-md font-bold text-white uppercase tracking-wider">Property Sales Revenue</h3>
                <span class="text-xs text-slate-400 font-bold">Total: ৳{{ number_format($sales->sum('sale_value'), 2) }}</span>
            </div>
            
            <div class="space-y-4">
                @forelse($sales as $sale)
                    <div class="p-3 bg-[#080B11] rounded-xl border border-slate-800/60 text-xs flex justify-between items-center">
                        <div>
                            <span class="font-bold text-white">{{ $sale->buyer_name }}</span>
                            <span class="text-slate-500 block mt-0.5">
                                @if($sale->property_type === 'plot')
                                    Plot No: {{ $sale->plot ? $sale->plot->plot_number : 'N/A' }}
                                @else
                                    Apt No: {{ $sale->apartment ? $sale->apartment->apartment_number : 'N/A' }}
                                @endif
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-emerald-400">৳{{ number_format($sale->sale_value, 2) }}</span>
                            <span class="text-[10px] text-slate-500 block mt-0.5">
                                {{ $sale->sale_date ? $sale->sale_date->format('d M, Y') : 'N/A' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-slate-500 italic text-xs">No property sales records matching dates.</div>
                @endforelse
            </div>
        </div>

        <!-- 3. Rental Agreement Log -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-xl space-y-6">
            <div class="flex justify-between items-center border-b border-slate-850 pb-2">
                <h3 class="text-md font-bold text-white uppercase tracking-wider">Rental Agreements & Income</h3>
                <span class="text-xs text-slate-400 font-bold">Total: ৳{{ number_format($rents->sum('monthly_rent'), 2) }}/mo</span>
            </div>
            
            <div class="space-y-4">
                @forelse($rents as $rent)
                    <div class="p-3 bg-[#080B11] rounded-xl border border-slate-800/60 text-xs flex justify-between items-center">
                        <div>
                            <span class="font-bold text-white">{{ $rent->tenant_name }}</span>
                            <span class="text-slate-500 block mt-0.5">
                                Apt {{ $rent->apartment ? $rent->apartment->apartment_number : 'N/A' }} 
                                ({{ $rent->apartment && $rent->apartment->floor && $rent->apartment->floor->building ? $rent->apartment->floor->building->name : 'N/A' }})
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-indigo-400">৳{{ number_format($rent->monthly_rent, 2) }}</span>
                            <span class="text-[10px] text-slate-500 block mt-0.5">
                                {{ $rent->rent_start_date ? $rent->rent_start_date->format('d M') : 'N/A' }} to {{ $rent->rent_end_date ? $rent->rent_end_date->format('d M, Y') : 'N/A' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-slate-550 italic text-xs">No active rental agreements matching dates.</div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
