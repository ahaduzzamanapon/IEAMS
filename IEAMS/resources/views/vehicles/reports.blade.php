@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-bold tracking-tight text-white">Vehicle Fleet Reports</h2>
        <p class="text-sm text-slate-400 mt-1">Audit fleet assignments, license expirations, and fitness validity status.</p>
    </div>

    <!-- Expirations Warnings -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- License Expiry Warnings -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-xl space-y-6">
            <h3 class="text-md font-bold text-white uppercase tracking-wider border-b border-slate-850 pb-2 flex items-center justify-between">
                <span>Registration Renewals Due</span>
                <span class="px-2 py-0.5 bg-rose-500/10 text-rose-400 text-[10px] font-bold rounded-full">Expirations</span>
            </h3>
            
            <div class="space-y-4">
                @php
                    $expiringRegs = $vehicles->filter(function($v) {
                        return $v->registration_expiry_date->isBefore(now()->addDays(30));
                    });
                @endphp
                @forelse($expiringRegs as $veh)
                    <div class="p-3 bg-[#080B11] rounded-xl border border-slate-800/60 text-xs flex justify-between items-center">
                        <div>
                            <span class="font-bold text-white">{{ $veh->vehicle_number }}</span>
                            <span class="text-slate-500 block mt-0.5">{{ $veh->brand }} {{ $veh->model }}</span>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-rose-400">{{ $veh->registration_expiry_date->format('d M, Y') }}</span>
                            <span class="text-[9px] text-slate-500 block mt-0.5">Expires in {{ now()->diffInDays($veh->registration_expiry_date, false) }} days</span>
                        </div>
                    </div>
                @empty
                    <div class="text-slate-650 italic text-xs">No vehicle registration expirations pending.</div>
                @endforelse
            </div>
        </div>

        <!-- Fitness Expiry Warnings -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-xl space-y-6">
            <h3 class="text-md font-bold text-white uppercase tracking-wider border-b border-slate-850 pb-2 flex items-center justify-between">
                <span>Fitness Certificates Due</span>
                <span class="px-2 py-0.5 bg-rose-500/10 text-rose-400 text-[10px] font-bold rounded-full">Fitness</span>
            </h3>
            
            <div class="space-y-4">
                @php
                    $expiringFitness = $vehicles->filter(function($v) {
                        return $v->fitness_expiry_date->isBefore(now()->addDays(30));
                    });
                @endphp
                @forelse($expiringFitness as $veh)
                    <div class="p-3 bg-[#080B11] rounded-xl border border-slate-800/60 text-xs flex justify-between items-center">
                        <div>
                            <span class="font-bold text-white">{{ $veh->vehicle_number }}</span>
                            <span class="text-slate-500 block mt-0.5">{{ $veh->brand }} {{ $veh->model }}</span>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-rose-400">{{ $veh->fitness_expiry_date->format('d M, Y') }}</span>
                            <span class="text-[9px] text-slate-500 block mt-0.5">Expires in {{ now()->diffInDays($veh->fitness_expiry_date, false) }} days</span>
                        </div>
                    </div>
                @empty
                    <div class="text-slate-650 italic text-xs">No fitness certificate expirations pending.</div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Active Assignments Report -->
    <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-xl">
        <h3 class="text-md font-bold text-white mb-6 uppercase tracking-wider">Active Fleet Allocation Report</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 text-xs uppercase font-medium">
                    <tr>
                        <th class="px-6 py-4">Vehicle</th>
                        <th class="px-6 py-4">Assigned Officer</th>
                        <th class="px-6 py-4">Assigned Driver</th>
                        <th class="px-6 py-4">Allocation Date</th>
                        <th class="px-6 py-4">Office & Purpose</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($assignments->where('status', 'active') as $as)
                        <tr class="hover:bg-slate-800/20">
                            <td class="px-6 py-4 text-xs font-semibold text-white">
                                {{ $as->vehicle->vehicle_number }}
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-slate-200">
                                {{ $as->officer->name }}
                            </td>
                            <td class="px-6 py-4 text-xs">
                                {{ $as->driver->name }} (License: {{ $as->driver->driving_license_number }})
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">
                                {{ $as->assignment_date->format('d M, Y') }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">
                                <div class="font-semibold">{{ $as->assigned_office }}</div>
                                <div class="text-[10px] text-slate-500">{{ $as->purpose }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">No vehicles are currently assigned.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
