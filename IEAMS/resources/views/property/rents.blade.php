@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-bold tracking-tight text-white">Rent Management</h2>
        <p class="text-sm text-slate-400 mt-1">Register and track rental lease agreements for vacant apartments.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Rent Form -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-4 lg:col-span-1">
            <h3 class="text-lg font-bold text-white">Create Rent Agreement</h3>
            <form action="{{ route('property.store-rent') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Select Vacant Apartment *</label>
                    <select name="apartment_id" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                        @foreach($apartments as $a)
                            <option value="{{ $a->id }}">Apt {{ $a->apartment_number }} - {{ $a->floor->building->name }} ({{ $a->floor->building->plot->project->name }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Tenant Name *</label>
                    <input type="text" name="tenant_name" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Tenant NID *</label>
                        <input type="text" name="nid" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Mobile *</label>
                        <input type="text" name="mobile" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Address *</label>
                    <textarea name="address" required rows="2" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2 text-sm text-white focus:outline-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Occupation *</label>
                    <input type="text" name="occupation" required placeholder="e.g. Service Holder, Businessman" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Rent Start Date *</label>
                        <input type="date" name="rent_start_date" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Rent End Date *</label>
                        <input type="date" name="rent_end_date" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1">Monthly Rent *</label>
                        <input type="number" step="0.01" name="monthly_rent" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-lg px-2 py-1.5 text-xs text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1">Advance (৳) *</label>
                        <input type="number" step="0.01" name="advance_amount" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-lg px-2 py-1.5 text-xs text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1">Deposit (৳) *</label>
                        <input type="number" step="0.01" name="security_deposit" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-lg px-2 py-1.5 text-xs text-white">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Agreement Number</label>
                    <input type="text" name="agreement_number" placeholder="AGR-XXXX" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>

                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition">
                    Register Agreement
                </button>
            </form>
        </div>

        <!-- Rent list -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 lg:col-span-2">
            <h3 class="text-lg font-bold text-white mb-6">Active Rent Agreements</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-900/60 text-slate-400 text-xs uppercase font-medium">
                        <tr>
                            <th class="px-6 py-4">Apartment</th>
                            <th class="px-6 py-4">Tenant Details</th>
                            <th class="px-6 py-4">Rent / Mo</th>
                            <th class="px-6 py-4">Timeline</th>
                            <th class="px-6 py-4">Agreement</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($rents as $rent)
                            <tr class="hover:bg-slate-800/20">
                                <td class="px-6 py-4 text-xs font-bold text-white">
                                    Apt {{ $rent->apartment->apartment_number }}
                                    <div class="text-slate-500 font-normal">Bldg: {{ $rent->apartment->floor->building->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <div class="font-semibold text-white">{{ $rent->tenant_name }}</div>
                                    <div class="text-slate-500">Mob: {{ $rent->mobile }} | Occupation: {{ $rent->occupation }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-white">
                                    ৳{{ number_format($rent->monthly_rent, 2) }}
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-400">
                                    {{ $rent->rent_start_date->format('d M, Y') }} to {{ $rent->rent_end_date->format('d M, Y') }}
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-slate-400">
                                    {{ $rent->agreement_number ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-xs text-right whitespace-nowrap">
                                    <button type="button" onclick="showEditModal({{ json_encode($rent) }})" class="px-2.5 py-1.5 bg-amber-600/20 hover:bg-amber-600/30 text-amber-400 font-semibold rounded-lg transition mr-1 cursor-pointer">
                                        Edit
                                    </button>
                                    <form action="{{ route('property.destroy-rent', $rent->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Rent Agreement?');" class="inline m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 bg-rose-600/20 hover:bg-rose-600/30 text-rose-400 font-semibold rounded-lg transition cursor-pointer">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">No rent agreements active.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $rents->links() }}
            </div>
        </div>

    </div>
</div>

<!-- Edit Rent Modal -->
<div id="editRentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden px-4">
    <div class="bg-[#0E1325] border border-slate-800 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-2">
            <h3 class="text-md font-bold text-white uppercase tracking-wider">Edit Rent Agreement</h3>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-200 cursor-pointer">✕</button>
        </div>
        
        <form id="editRentForm" method="POST" class="space-y-4 text-sm text-slate-350">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Apartment *</label>
                <select id="edit_apartment_id" name="apartment_id" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
                    @foreach($rents as $r)
                        <option value="{{ $r->apartment->id }}">Apt {{ $r->apartment->apartment_number }} - {{ $r->apartment->floor->building->name }} (Active Tenant: {{ $r->tenant_name }})</option>
                    @endforeach
                    @foreach($apartments as $a)
                        <option value="{{ $a->id }}">Apt {{ $a->apartment_number }} - {{ $a->floor->building->name }} (Vacant)</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Tenant Name *</label>
                <input type="text" id="edit_tenant_name" name="tenant_name" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Tenant NID *</label>
                    <input type="text" id="edit_nid" name="nid" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Mobile *</label>
                    <input type="text" id="edit_mobile" name="mobile" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Address *</label>
                <textarea id="edit_address" name="address" required rows="2" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Occupation *</label>
                <input type="text" id="edit_occupation" name="occupation" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Rent Start Date *</label>
                    <input type="date" id="edit_rent_start_date" name="rent_start_date" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Rent End Date *</label>
                    <input type="date" id="edit_rent_end_date" name="rent_end_date" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-2">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Rent *</label>
                    <input type="number" step="0.01" id="edit_monthly_rent" name="monthly_rent" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-lg px-2 py-1 text-xs text-white">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Advance *</label>
                    <input type="number" step="0.01" id="edit_advance_amount" name="advance_amount" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-lg px-2 py-1 text-xs text-white">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Deposit *</label>
                    <input type="number" step="0.01" id="edit_security_deposit" name="security_deposit" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-lg px-2 py-1 text-xs text-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Agreement Number</label>
                <input type="text" id="edit_agreement_number" name="agreement_number" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
            </div>

            <div class="flex items-center gap-3 pt-2 justify-end">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-705 text-slate-300 font-semibold text-xs rounded-xl transition cursor-pointer">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs rounded-xl transition cursor-pointer">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showEditModal(rent) {
        document.getElementById('editRentForm').action = `/property/rents/${rent.id}`;
        
        document.getElementById('edit_apartment_id').value = rent.apartment_id;
        document.getElementById('edit_tenant_name').value = rent.tenant_name;
        document.getElementById('edit_nid').value = rent.nid;
        document.getElementById('edit_mobile').value = rent.mobile;
        document.getElementById('edit_address').value = rent.address;
        document.getElementById('edit_occupation').value = rent.occupation;
        
        if (rent.rent_start_date) {
            document.getElementById('edit_rent_start_date').value = rent.rent_start_date.substring(0, 10);
        }
        if (rent.rent_end_date) {
            document.getElementById('edit_rent_end_date').value = rent.rent_end_date.substring(0, 10);
        }
        
        document.getElementById('edit_monthly_rent').value = rent.monthly_rent;
        document.getElementById('edit_advance_amount').value = rent.advance_amount;
        document.getElementById('edit_security_deposit').value = rent.security_deposit;
        document.getElementById('edit_agreement_number').value = rent.agreement_number || '';
        
        document.getElementById('editRentModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editRentModal').classList.add('hidden');
    }
</script>
@endsection
