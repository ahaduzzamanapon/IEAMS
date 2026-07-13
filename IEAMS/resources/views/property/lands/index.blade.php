@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">Land Registry</h2>
            <p class="text-sm text-slate-400 mt-1">Manage and track land purchases, registration details, and deed information of NHA projects.</p>
        </div>
        <div>
            <a href="{{ route('property.lands.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition shadow-lg shadow-indigo-600/20">
                + Register New Land
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="p-4 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('property.lands.index') }}" method="GET" class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by seller, deed, khatian, dag, or project..." class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-800 hover:bg-slate-700 text-white font-medium text-xs rounded-xl transition cursor-pointer">
                    Search
                </button>
                <a href="{{ route('property.lands.index') }}" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800/60 text-slate-400 hover:text-slate-200 font-medium text-xs rounded-xl transition flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Land Table List -->
    <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 text-xs uppercase font-medium">
                    <tr>
                        <th class="px-6 py-4">Project</th>
                        <th class="px-6 py-4">Purchase Details</th>
                        <th class="px-6 py-4">Registration & Deed</th>
                        <th class="px-6 py-4">Land Area & Class</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($lands as $land)
                        <tr class="hover:bg-slate-800/20 transition duration-150">
                            <td class="px-6 py-4">
                                @if($land->project)
                                    <div class="font-semibold text-white">{{ $land->project->name }}</div>
                                    <div class="text-xs text-slate-500 font-mono">{{ $land->project->project_id_code }}</div>
                                @else
                                    <span class="text-slate-500 italic text-xs">No project linked</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-200">৳{{ number_format($land->purchase_value, 2) }}</div>
                                <div class="text-xs text-slate-500">Date: {{ $land->purchase_date->format('M d, Y') }}</div>
                                <div class="text-xs text-slate-550 italic">Seller: {{ $land->seller_information }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">
                                <div>Deed No: {{ $land->deed_number }}</div>
                                <div>Reg Date: {{ $land->registration_date->format('M d, Y') }}</div>
                                <div class="text-[10px] text-slate-550 font-mono">Kh: {{ $land->khatian_number }} | Dag: {{ $land->dag_number }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-semibold text-white">{{ $land->land_amount }} Acres</div>
                                <div class="mb-1">
                                    <span class="px-2 py-0.5 bg-slate-850 rounded text-[9px] text-slate-400 font-mono">
                                        {{ $land->land_classification }}
                                    </span>
                                </div>
                                @if($land->land_map_path)
                                    <div class="mt-1">
                                        <a href="{{ asset('storage/' . $land->land_map_path) }}" target="_blank" class="inline-flex items-center text-[10px] text-indigo-400 hover:text-indigo-300 font-semibold transition">
                                            📂 View Layout Plan
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('property.lands.edit', $land->id) }}" class="px-3 py-1.5 bg-indigo-600/10 hover:bg-indigo-600 text-indigo-400 hover:text-white font-medium text-xs rounded-lg transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('property.lands.destroy', $land->id) }}" method="POST" class="inline m-0" onsubmit="return confirm('Are you sure you want to delete this land detail?');">
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
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">No land details registered matching criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($lands->hasPages())
            <div class="mt-6">
                {{ $lands->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
