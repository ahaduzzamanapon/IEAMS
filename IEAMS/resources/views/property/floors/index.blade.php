@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">Floor Management</h2>
            <p class="text-sm text-slate-400 mt-1">Configure and manage specific floors and expected apartment capacity in NHA structures.</p>
        </div>
        <div>
            <a href="{{ route('property.floors.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition shadow-lg shadow-indigo-600/20">
                + Add Floor
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="p-4 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('property.floors.index') }}" method="GET" class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by floor number, floor name, building, or project..." class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-800 hover:bg-slate-700 text-white font-medium text-xs rounded-xl transition cursor-pointer">
                    Search
                </button>
                <a href="{{ route('property.floors.index') }}" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800/60 text-slate-400 hover:text-slate-200 font-medium text-xs rounded-xl transition flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Floor Table List -->
    <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 text-xs uppercase font-medium">
                    <tr>
                        <th class="px-6 py-4">Building Name</th>
                        <th class="px-6 py-4">Floor Number</th>
                        <th class="px-6 py-4">Floor Name/Alias</th>
                        <th class="px-6 py-4">Capacity (Total Apts)</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($floors as $floor)
                        <tr class="hover:bg-slate-800/20 transition duration-150">
                            <td class="px-6 py-4">
                                @if($floor->building)
                                    <div class="font-semibold text-white">{{ $floor->building->name }}</div>
                                    <div class="text-xs text-slate-500 font-mono">Bldg No: {{ $floor->building->number }}</div>
                                    @if($floor->building->plot && $floor->building->plot->project)
                                        <div class="text-[10px] text-slate-550 italic">Project: {{ $floor->building->plot->project->name }}</div>
                                    @endif
                                @else
                                    <span class="text-slate-500 italic">No building linked</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-semibold text-white tracking-wider">
                                {{ $floor->floor_number }}
                            </td>
                            <td class="px-6 py-4 text-slate-300">
                                {{ $floor->floor_name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-slate-200">
                                {{ $floor->total_apartment }} Apartments planned
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('property.floors.edit', $floor->id) }}" class="px-3 py-1.5 bg-indigo-600/10 hover:bg-indigo-600 text-indigo-400 hover:text-white font-medium text-xs rounded-lg transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('property.floors.destroy', $floor->id) }}" method="POST" class="inline m-0" onsubmit="return confirm('Are you sure you want to delete this floor?');">
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
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">No floors registered matching criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($floors->hasPages())
            <div class="mt-6">
                {{ $floors->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
