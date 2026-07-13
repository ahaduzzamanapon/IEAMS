@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">Apartment Register</h2>
            <p class="text-sm text-slate-400 mt-1">Manage and track NHA flats, sizes, specifications, utility statuses, and occupancy cycles.</p>
        </div>
        <div>
            <a href="{{ route('property.apartments.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition shadow-lg shadow-indigo-600/20">
                + Register Apartment
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="p-4 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('property.apartments.index') }}" method="GET" class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by number, name, parking spot, building..." class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <select name="status" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="vacant" {{ request('status') === 'vacant' ? 'selected' : '' }}>Vacant</option>
                    <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="booked" {{ request('status') === 'booked' ? 'selected' : '' }}>Booked</option>
                    <option value="allocated" {{ request('status') === 'allocated' ? 'selected' : '' }}>Allocated</option>
                    <option value="rented" {{ request('status') === 'rented' ? 'selected' : '' }}>Rented</option>
                    <option value="sold" {{ request('status') === 'sold' ? 'selected' : '' }}>Sold</option>
                    <option value="under_maintenance" {{ request('status') === 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-800 hover:bg-slate-700 text-white font-medium text-xs rounded-xl transition cursor-pointer">
                    Apply
                </button>
                <a href="{{ route('property.apartments.index') }}" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800/60 text-slate-400 hover:text-slate-200 font-medium text-xs rounded-xl transition flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Apartment Table List -->
    <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 text-xs uppercase font-medium">
                    <tr>
                        <th class="px-6 py-4">Apartment Details</th>
                        <th class="px-6 py-4">Structure Position</th>
                        <th class="px-6 py-4">Specs & Size</th>
                        <th class="px-6 py-4">Utilities & Parking</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($apartments as $apt)
                        <tr class="hover:bg-slate-800/20 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-white tracking-wider">{{ $apt->apartment_number }}</div>
                                <div class="text-xs text-slate-400">{{ $apt->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                @if($apt->floor && $apt->floor->building)
                                    <div class="font-medium text-slate-200">{{ $apt->floor->building->name }}</div>
                                    <div class="text-slate-500">{{ $apt->floor->floor_number }}</div>
                                    @if($apt->floor->building->plot && $apt->floor->building->plot->project)
                                        <div class="text-[9px] text-slate-550 font-mono">Proj: {{ $apt->floor->building->plot->project->name }}</div>
                                    @endif
                                @else
                                    <span class="text-slate-550 italic">No floor registry</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-300">
                                <div class="font-mono text-slate-200">{{ $apt->size }} Sq. Ft.</div>
                                <div class="text-slate-500 font-mono">Bed: {{ $apt->bedrooms ?? 0 }} | Bath: {{ $apt->bathrooms ?? 0 }} | Balc: {{ $apt->balcony ?? 0 }}</div>
                                <div class="text-[10px] text-slate-550">Orientation: {{ $apt->orientation }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <div class="flex flex-col gap-1">
                                    <span class="px-2 py-0.5 rounded text-[9px] w-max font-semibold {{ $apt->utility_connection ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">
                                        {{ $apt->utility_connection ? '🔌 CONNECTED' : '🔌 NO CONNECTION' }}
                                    </span>
                                    @if($apt->parking)
                                        <span class="px-2 py-0.5 rounded text-[9px] w-max font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                            🅿 {{ $apt->parking }}
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[9px] w-max bg-slate-850 text-slate-500">
                                            🅿 NO PARKING
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-full {{ $apt->status === 'vacant' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($apt->status === 'sold' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : ($apt->status === 'under_maintenance' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20')) }}">
                                    {{ strtoupper($apt->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('property.apartments.edit', $apt->id) }}" class="px-3 py-1.5 bg-indigo-600/10 hover:bg-indigo-600 text-indigo-400 hover:text-white font-medium text-xs rounded-lg transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('property.apartments.destroy', $apt->id) }}" method="POST" class="inline m-0" onsubmit="return confirm('Are you sure you want to delete this apartment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-rose-600/10 hover:bg-rose-600 text-rose-400 hover:text-white font-medium text-xs rounded-lg transition cursor-pointer">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">No apartments registered matching criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($apartments->hasPages())
            <div class="mt-6">
                {{ $apartments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
