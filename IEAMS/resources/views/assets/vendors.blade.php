@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-bold tracking-tight text-white">Vendor Registry</h2>
        <p class="text-sm text-slate-400 mt-1">Manage vendor contacts and sourcing sources.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Create Vendor Form -->
        <div class="p-6 rounded-2xl bg-[#0E132F]/80 border border-slate-800/80 space-y-6 lg:col-span-1">
            <h3 class="text-lg font-bold text-white">Register Vendor</h3>
            <form action="{{ route('vendors.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Vendor Name</label>
                    <input type="text" name="name" required placeholder="e.g. Dell Bangladesh, Walton Group" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Contact Person</label>
                    <input type="text" name="contact_person" placeholder="e.g. Mr. Rahat Ali" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Mobile Number</label>
                    <input type="text" name="mobile" placeholder="e.g. +88017XXXXXXXX" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Email Address</label>
                    <input type="email" name="email" placeholder="e.g. sales@vendor.com" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Address</label>
                    <textarea name="address" rows="3" placeholder="e.g. Motijheel C/A, Dhaka" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500"></textarea>
                </div>
                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition">
                    Save Vendor
                </button>
            </form>
        </div>

        <!-- Vendor List -->
        <div class="p-6 rounded-2xl bg-[#0E132F]/80 border border-slate-800/80 lg:col-span-2">
            <h3 class="text-lg font-bold text-white mb-6">Registered Vendors</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-900/60 text-slate-400 text-xs uppercase font-medium">
                        <tr>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Contact Info</th>
                            <th class="px-6 py-4">Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($vendors as $vendor)
                            <tr class="hover:bg-slate-800/20">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-white">{{ $vendor->name }}</div>
                                    <div class="text-xs text-slate-500">Contact: {{ $vendor->contact_person ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-slate-300">{{ $vendor->mobile ?? 'N/A' }}</div>
                                    <div class="text-xs text-slate-500">{{ $vendor->email ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-400">
                                    {{ $vendor->address ?? 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-slate-500">No vendors registered. Create a vendor on the left to start.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $vendors->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
