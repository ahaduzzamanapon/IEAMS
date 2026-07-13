@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">Vehicle Fleet Registry</h2>
            <p class="text-sm text-slate-400 mt-1">Monitor government transport vehicles, licensing, and fitness renewals.</p>
        </div>
        <div>
            <a href="{{ route('vehicles.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition shadow-lg shadow-indigo-600/20">
                + Register New Vehicle
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="p-4 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('vehicles.index') }}" method="GET" class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search vehicle number, brand..." class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <select name="type" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
                    <option value="">All Vehicle Types</option>
                    <option value="sedan" {{ request('type') === 'sedan' ? 'selected' : '' }}>Sedan Car</option>
                    <option value="jeep" {{ request('type') === 'jeep' ? 'selected' : '' }}>SUV/Jeep</option>
                    <option value="microbus" {{ request('type') === 'microbus' ? 'selected' : '' }}>Microbus</option>
                    <option value="truck" {{ request('type') === 'truck' ? 'selected' : '' }}>Truck</option>
                </select>
            </div>
            <div>
                <select name="status" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="under_maintenance" {{ request('status') === 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                    <option value="out_of_service" {{ request('status') === 'out_of_service' ? 'selected' : '' }}>Out of Service</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-800 hover:bg-slate-700 text-white font-medium text-xs rounded-xl transition">
                    Apply Filter
                </button>
                <a href="{{ route('vehicles.index') }}" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-400 font-medium text-xs rounded-xl transition flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Vehicles List Grid -->
    <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 text-xs uppercase font-medium">
                    <tr>
                        <th class="px-6 py-4">Vehicle Details</th>
                        <th class="px-6 py-4">Registration & Fitness</th>
                        <th class="px-6 py-4">Chassis & Engine</th>
                        <th class="px-6 py-4">Fuel & Seating</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($vehicles as $veh)
                        <tr class="hover:bg-slate-800/20">
                            <td class="px-6 py-4">
                                <div class="font-bold text-white tracking-wider text-sm">{{ $veh->vehicle_number }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">{{ $veh->brand }} {{ $veh->model }} ({{ $veh->vehicle_type }})</div>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <div>Reg Expiry: <span class="text-white">{{ $veh->registration_expiry_date->format('d M, Y') }}</span></div>
                                <div class="text-slate-500">Fitness Expiry: {{ $veh->fitness_expiry_date->format('d M, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-slate-400">
                                <div>Chassis: {{ $veh->chassis_number }}</div>
                                <div>Engine: {{ $veh->engine_number }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <div>Fuel capacity: {{ $veh->fuel_quantity }} L ({{ $veh->fuel_type }})</div>
                                <div class="text-slate-500">Capacity: {{ $veh->seating_capacity }} seats</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-full {{ $veh->status === 'available' ? 'bg-emerald-500/10 text-emerald-400' : ($veh->status === 'assigned' ? 'bg-indigo-500/10 text-indigo-400' : 'bg-rose-500/10 text-rose-400') }}">
                                    {{ strtoupper($veh->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('vehicles.show', $veh->id) }}" class="px-3 py-1.5 bg-indigo-600/10 hover:bg-indigo-600 text-indigo-400 hover:text-white font-medium text-xs rounded-lg transition">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">No vehicles registered matching criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $vehicles->links() }}
        </div>
    </div>
</div>
@endsection
