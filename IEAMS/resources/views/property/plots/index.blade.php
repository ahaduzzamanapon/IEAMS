@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">Plot Management</h2>
            <p class="text-sm text-slate-400 mt-1">Manage project plots, areas, and allocation status.</p>
        </div>
        <div>
            <a href="{{ route('property.plots.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition shadow-lg shadow-indigo-600/20">
                + Register New Plot
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="p-4 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('property.plots.index') }}" method="GET" class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by plot number, name, or project..." class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <select name="status" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="vacant" {{ request('status') === 'vacant' ? 'selected' : '' }}>Vacant</option>
                    <option value="sold" {{ request('status') === 'sold' ? 'selected' : '' }}>Sold</option>
                    <option value="leased" {{ request('status') === 'leased' ? 'selected' : '' }}>Leased</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-800 hover:bg-slate-700 text-white font-medium text-xs rounded-xl transition cursor-pointer">
                    Apply
                </button>
                <a href="{{ route('property.plots.index') }}" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800/60 text-slate-400 hover:text-slate-200 font-medium text-xs rounded-xl transition flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Plot Table List -->
    <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 text-xs uppercase font-medium">
                    <tr>
                        <th class="px-6 py-4">Project</th>
                        <th class="px-6 py-4">Plot Number</th>
                        <th class="px-6 py-4">Plot Name</th>
                        <th class="px-6 py-4">Area (Sq. Ft.)</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($plots as $plot)
                        <tr class="hover:bg-slate-800/20 transition duration-150">
                            <td class="px-6 py-4">
                                @if($plot->project)
                                    <div class="font-semibold text-white">{{ $plot->project->name }}</div>
                                    <div class="text-xs text-slate-500 font-mono">{{ $plot->project->project_id_code }}</div>
                                @else
                                    <span class="text-slate-500 italic text-xs">No project linked</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-semibold text-white tracking-wider">
                                {{ $plot->plot_number }}
                            </td>
                            <td class="px-6 py-4 text-slate-300">
                                {{ $plot->plot_name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-slate-200">
                                {{ number_format($plot->plot_area, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-full {{ $plot->status === 'vacant' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($plot->status === 'sold' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20') }}">
                                    {{ strtoupper($plot->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('property.plots.edit', $plot->id) }}" class="px-3 py-1.5 bg-indigo-600/10 hover:bg-indigo-600 text-indigo-400 hover:text-white font-medium text-xs rounded-lg transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('property.plots.destroy', $plot->id) }}" method="POST" class="inline m-0" onsubmit="return confirm('Are you sure you want to delete this plot?');">
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
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">No plots registered matching criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($plots->hasPages())
            <div class="mt-6">
                {{ $plots->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
