@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-bold tracking-tight text-white">Driver Management</h2>
        <p class="text-sm text-slate-400 mt-1">Manage transport drivers, licensing details, and active statuses.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Register Driver Form -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-4 lg:col-span-1">
            <h3 class="text-lg font-bold text-white">Register Driver</h3>
            <form action="{{ route('vehicles.store-driver') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Driver Name *</label>
                    <input type="text" name="name" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Father's Name *</label>
                    <input type="text" name="father_name" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Mobile Number *</label>
                        <input type="text" name="mobile" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">NID *</label>
                        <input type="text" name="nid" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Driving License Number *</label>
                    <input type="text" name="driving_license_number" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">License Issue Date *</label>
                        <input type="date" name="license_issue_date" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">License Expiry *</label>
                        <input type="date" name="license_expiry_date" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">License Class *</label>
                        <input type="text" name="license_category" placeholder="e.g. Light, Medium" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Blood Group *</label>
                        <input type="text" name="blood_group" placeholder="e.g. A+" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Emergency Contact Number *</label>
                    <input type="text" name="emergency_contact" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Present Address *</label>
                    <textarea name="present_address" required rows="2" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2 text-sm text-white focus:outline-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Permanent Address *</label>
                    <textarea name="permanent_address" required rows="2" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2 text-sm text-white focus:outline-none"></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition">
                    Register Driver
                </button>
            </form>
        </div>

        <!-- Drivers list -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 lg:col-span-2">
            <h3 class="text-lg font-bold text-white mb-6">Registered Drivers</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-900/60 text-slate-400 text-xs uppercase font-medium">
                        <tr>
                            <th class="px-6 py-4">Name & Father Name</th>
                            <th class="px-6 py-4">Mobile & NID</th>
                            <th class="px-6 py-4">License Number</th>
                            <th class="px-6 py-4">Expiry Date</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($drivers as $driver)
                            <tr class="hover:bg-slate-800/20">
                                <td class="px-6 py-4 text-xs font-semibold text-white">
                                    {{ $driver->name }}
                                    <div class="text-slate-500 font-normal">F: {{ $driver->father_name }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <div>Mob: {{ $driver->mobile }}</div>
                                    <div class="text-slate-500">NID: {{ $driver->nid }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-slate-300">
                                    {{ $driver->driving_license_number }}
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    {{ $driver->license_expiry_date ? $driver->license_expiry_date->format('d M, Y') : 'Lifetime' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 text-[9px] font-bold rounded-full {{ $driver->status === 'active' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }}">
                                        {{ strtoupper($driver->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">No drivers registered.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $drivers->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
