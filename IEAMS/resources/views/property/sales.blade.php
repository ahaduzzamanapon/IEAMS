@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-bold tracking-tight text-white">Property Sales Registry</h2>
        <p class="text-sm text-slate-400 mt-1">Manage and register sales of vacant plots and apartments.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Sale Form -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-4 lg:col-span-1">
            <h3 class="text-lg font-bold text-white">Record Property Sale</h3>
            <form action="{{ route('property.store-sale') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Property Type *</label>
                    <select name="property_type" id="prop_type" onchange="togglePropType()" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="plot">Plot</option>
                        <option value="apartment">Apartment</option>
                    </select>
                </div>

                <div id="plot_option">
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Select Vacant Plot *</label>
                    <select name="plot_id" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                        @foreach($plots as $p)
                            <option value="{{ $p->id }}">Plot {{ $p->plot_number }} - {{ $p->project->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="apt_option" class="hidden">
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Select Vacant Apartment *</label>
                    <select name="apartment_id" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                        @foreach($apartments as $a)
                            <option value="{{ $a->id }}">Apt {{ $a->apartment_number }} - {{ $a->floor->building->name }} ({{ $a->floor->building->plot->project->name }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Buyer Name *</label>
                    <input type="text" name="buyer_name" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Father's Name *</label>
                        <input type="text" name="father_name" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Mother's Name *</label>
                        <input type="text" name="mother_name" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">NID *</label>
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
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Sale Value (৳) *</label>
                        <input type="number" step="0.01" name="sale_value" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Sale Date *</label>
                        <input type="date" name="sale_date" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Payment Status *</label>
                    <select name="payment_status" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                        <option value="pending">Pending</option>
                        <option value="partially_paid">Partially Paid</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition">
                    Register Sale
                </button>
            </form>
        </div>

        <!-- Sales List -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 lg:col-span-2">
            <h3 class="text-lg font-bold text-white mb-6">Registered Property Sales</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-900/60 text-slate-400 text-xs uppercase font-medium">
                        <tr>
                            <th class="px-6 py-4">Property</th>
                            <th class="px-6 py-4">Buyer Details</th>
                            <th class="px-6 py-4">Sale Value</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Payment</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($sales as $sale)
                            <tr class="hover:bg-slate-800/20">
                                <td class="px-6 py-4 text-xs">
                                    @if($sale->property_type === 'plot')
                                        <div class="font-bold text-white">Plot No: {{ $sale->plot->plot_number }}</div>
                                        <div class="text-slate-500">Site: {{ $sale->plot->project->name }}</div>
                                    @else
                                        <div class="font-bold text-white">Apt No: {{ $sale->apartment->apartment_number }}</div>
                                        <div class="text-slate-500">Bldg: {{ $sale->apartment->floor->building->name }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <div class="font-semibold text-white">{{ $sale->buyer_name }}</div>
                                    <div class="text-slate-500">NID: {{ $sale->nid }} | Mob: {{ $sale->mobile }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-white">
                                    ৳{{ number_format($sale->sale_value, 2) }}
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    {{ $sale->sale_date->format('d M, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-[9px] font-bold rounded-full {{ $sale->payment_status === 'paid' ? 'bg-emerald-500/10 text-emerald-400' : ($sale->payment_status === 'partially_paid' ? 'bg-amber-500/10 text-amber-400' : 'bg-rose-500/10 text-rose-400') }}">
                                        {{ strtoupper(str_replace('_', ' ', $sale->payment_status)) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">No property sales registered.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $sales->links() }}
            </div>
        </div>

    </div>
</div>

<script>
    function togglePropType() {
        const type = document.getElementById('prop_type').value;
        const plotOpt = document.getElementById('plot_option');
        const aptOpt = document.getElementById('apt_option');

        if (type === 'plot') {
            plotOpt.classList.remove('hidden');
            aptOpt.classList.add('hidden');
        } else {
            plotOpt.classList.add('hidden');
            aptOpt.classList.remove('hidden');
        }
    }
</script>
@endsection
