@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">Asset Register</h2>
            <p class="text-sm text-slate-400 mt-1">Maintain and track all NHA Tangible, Current, and Consumer Assets.</p>
        </div>
        <div>
            <a href="{{ route('assets.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition shadow-lg shadow-indigo-600/20">
                + Register New Asset
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="p-4 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('assets.index') }}" method="GET" class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ID, brand, serial..." class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <select name="type" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
                    <option value="">All Asset Types</option>
                    <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Fixed Asset</option>
                    <option value="current" {{ request('type') === 'current' ? 'selected' : '' }}>Current Asset</option>
                    <option value="consumer" {{ request('type') === 'consumer' ? 'selected' : '' }}>Consumer asset</option>
                </select>
            </div>
            <div>
                <select name="status" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="under_maintenance" {{ request('status') === 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                    <option value="scrap" {{ request('status') === 'scrap' ? 'selected' : '' }}>Scrap</option>
                    <option value="disposed" {{ request('status') === 'disposed' ? 'selected' : '' }}>Disposed</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-800 hover:bg-slate-700 text-white font-medium text-xs rounded-xl transition">
                    Apply Filter
                </button>
                <a href="{{ route('assets.index') }}" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800/60 text-slate-400 hover:text-slate-200 font-medium text-xs rounded-xl transition flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Asset Table List -->
    <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 text-xs uppercase font-medium">
                    <tr>
                        <th class="px-6 py-4">Asset ID / Details</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Serial Number</th>
                        <th class="px-6 py-4">Current Value / Qty</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($assets as $asset)
                        <tr class="hover:bg-slate-800/20">
                            <td class="px-6 py-4">
                                @if($asset->unique_asset_id)
                                    <div class="font-semibold text-white tracking-wider text-xs">{{ $asset->unique_asset_id }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $asset->brand }} - {{ $asset->model }}</div>
                                @else
                                    <div class="font-semibold text-white italic text-xs">Consumer Asset Registry</div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $asset->category->name }} (No Unique ID)</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-medium text-slate-200">{{ $asset->category->name }}</div>
                                <div class="text-[10px] text-slate-500">{{ $asset->subCategory->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-full {{ $asset->asset_type === 'fixed' ? 'bg-indigo-500/10 text-indigo-400' : ($asset->asset_type === 'current' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-amber-500/10 text-amber-400') }}">
                                    {{ strtoupper($asset->asset_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-slate-400">
                                {{ $asset->serial_number ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($asset->asset_type === 'consumer')
                                    <div class="text-xs font-medium text-slate-200">Qty: {{ $asset->quantity ?? '0' }}</div>
                                @else
                                    <div class="text-xs font-semibold text-white">৳{{ number_format($asset->current_book_value, 2) }}</div>
                                    <div class="text-[10px] text-slate-500">Cost: ৳{{ number_format($asset->total_cost, 2) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full {{ $asset->maintenance_status === 'available' ? 'bg-emerald-500/10 text-emerald-400' : ($asset->maintenance_status === 'assigned' ? 'bg-indigo-500/10 text-indigo-400' : 'bg-rose-500/10 text-rose-400') }}">
                                    {{ ucfirst(str_replace('_', ' ', $asset->maintenance_status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('assets.show', $asset->id) }}" class="px-3 py-1.5 bg-indigo-600/10 hover:bg-indigo-600 text-indigo-400 hover:text-white font-medium text-xs rounded-lg transition">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">No assets registered matching criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $assets->links() }}
        </div>
    </div>
</div>
@endsection
