@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-bold tracking-tight text-white">Asset Reports</h2>
        <p class="text-sm text-slate-400 mt-1">Generate lists, audits, and value reports across NHA registries.</p>
    </div>

    <!-- Filters Panel -->
    <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-lg">
        <form action="{{ route('assets.reports') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Asset Type</label>
                <select name="type" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500">
                    <option value="">All Types</option>
                    <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Fixed Asset</option>
                    <option value="current" {{ request('type') === 'current' ? 'selected' : '' }}>Current Asset</option>
                    <option value="consumer" {{ request('type') === 'consumer' ? 'selected' : '' }}>Consumer Asset</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Asset Category</label>
                <select name="category_id" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Status</label>
                <select name="status" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="under_maintenance" {{ request('status') === 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                    <option value="scrap" {{ request('status') === 'scrap' ? 'selected' : '' }}>Scrap</option>
                    <option value="disposed" {{ request('status') === 'disposed' ? 'selected' : '' }}>Disposed</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-xs rounded-xl transition">
                    Generate Report
                </button>
                <a href="{{ route('assets.reports') }}" class="px-4 py-2.5 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-400 font-medium text-xs rounded-xl transition flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Reports Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-md">
            <div class="text-xs font-semibold text-slate-500 uppercase">Items Matching</div>
            <div class="text-3xl font-bold text-indigo-400 mt-2">{{ $assets->count() }}</div>
        </div>
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-md">
            <div class="text-xs font-semibold text-slate-500 uppercase">Total Sourcing Cost</div>
            <div class="text-3xl font-bold text-white mt-2">৳{{ number_format($assets->sum('total_cost'), 2) }}</div>
        </div>
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-md">
            <div class="text-xs font-semibold text-slate-500 uppercase">Current Asset Book Value</div>
            <div class="text-3xl font-bold text-emerald-400 mt-2">৳{{ number_format($assets->sum('current_book_value'), 2) }}</div>
        </div>
    </div>

    <!-- Report Table List -->
    <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-white">Audit Ready Asset List</h3>
            <button onclick="window.print()" class="px-4 py-1.5 bg-slate-800 hover:bg-slate-700 text-white font-medium text-xs rounded-lg transition">
                🖨️ Print Report
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 text-xs uppercase font-medium">
                    <tr>
                        <th class="px-6 py-4">Asset ID</th>
                        <th class="px-6 py-4">Category & Sub-Category</th>
                        <th class="px-6 py-4">Brand / Model</th>
                        <th class="px-6 py-4">Purchase Cost</th>
                        <th class="px-6 py-4">Book Value</th>
                        <th class="px-6 py-4">Current Custodian</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($assets as $asset)
                        <tr class="hover:bg-slate-800/20">
                            <td class="px-6 py-4 font-mono text-xs font-semibold text-white">
                                {{ $asset->unique_asset_id ?? 'N/A (Consumer)' }}
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <div>{{ $asset->category->name }}</div>
                                <div class="text-slate-500 text-[10px]">{{ $asset->subCategory->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                {{ $asset->brand ?? 'N/A' }} {{ $asset->model ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold">
                                ৳{{ number_format($asset->total_cost, 2) }}
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-emerald-400">
                                ৳{{ number_format($asset->current_book_value, 2) }}
                            </td>
                            <td class="px-6 py-4 text-xs">
                                @php
                                    $activeAssign = $asset->assignments()->where('status', 'active')->first();
                                @endphp
                                {{ $activeAssign ? $activeAssign->custodian->name : 'Unassigned (NHA Store)' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full {{ $asset->maintenance_status === 'available' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-indigo-500/10 text-indigo-400' }}">
                                    {{ ucfirst($asset->maintenance_status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">No assets matching criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
