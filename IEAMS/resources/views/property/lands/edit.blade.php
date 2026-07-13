@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">Edit Land details</h2>
            <p class="text-sm text-slate-400 mt-1">Modify registered land details and deeds associated with an NHA project.</p>
        </div>
        <a href="{{ route('property.lands.index') }}" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 hover:text-white font-medium text-sm rounded-xl transition">
            ← Back to Lands
        </a>
    </div>

    <!-- Validation Errors -->
    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">
            <p class="font-semibold mb-2">Please fix the following validation errors:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="p-8 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-xl">
        <form action="{{ route('property.lands.update', $land->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Project Dependency Choice -->
            <div class="space-y-1.5">
                <label for="project_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Housing Project *</label>
                <select name="project_id" id="project_id" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                    <option value="">Select Parent Project</option>
                    @foreach($projects as $proj)
                        <option value="{{ $proj->id }}" {{ old('project_id', $land->project_id) == $proj->id ? 'selected' : '' }}>
                            {{ $proj->name }} ({{ $proj->project_id_code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Purchase Date -->
                <div class="space-y-1.5">
                    <label for="purchase_date" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Purchase Date *</label>
                    <input type="date" name="purchase_date" id="purchase_date" required value="{{ old('purchase_date', $land->purchase_date ? $land->purchase_date->format('Y-m-d') : '') }}" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <!-- Registration Date -->
                <div class="space-y-1.5">
                    <label for="registration_date" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Registration Date *</label>
                    <input type="date" name="registration_date" id="registration_date" required value="{{ old('registration_date', $land->registration_date ? $land->registration_date->format('Y-m-d') : '') }}" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Purchase Value -->
                <div class="space-y-1.5">
                    <label for="purchase_value" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Purchase Value (৳) *</label>
                    <input type="number" step="0.01" name="purchase_value" id="purchase_value" required value="{{ old('purchase_value', $land->purchase_value) }}" placeholder="e.g. 5000000.00" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <!-- Land Amount -->
                <div class="space-y-1.5">
                    <label for="land_amount" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Land Amount (Acres) *</label>
                    <input type="number" step="0.01" name="land_amount" id="land_amount" required value="{{ old('land_amount', $land->land_amount) }}" placeholder="e.g. 2.45" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Deed Number -->
                <div class="space-y-1.5">
                    <label for="deed_number" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Deed Number *</label>
                    <input type="text" name="deed_number" id="deed_number" required value="{{ old('deed_number', $land->deed_number) }}" placeholder="e.g. DEED-4432" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <!-- Land Classification -->
                <div class="space-y-1.5">
                    <label for="land_classification" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Land Classification *</label>
                    <input type="text" name="land_classification" id="land_classification" required value="{{ old('land_classification', $land->land_classification) }}" placeholder="e.g. Residential Flat Land" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Khatian Number -->
                <div class="space-y-1.5">
                    <label for="khatian_number" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Khatian Number *</label>
                    <input type="text" name="khatian_number" id="khatian_number" required value="{{ old('khatian_number', $land->khatian_number) }}" placeholder="e.g. CS-12, RS-45" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <!-- Dag Number -->
                <div class="space-y-1.5">
                    <label for="dag_number" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Dag Number *</label>
                    <input type="text" name="dag_number" id="dag_number" required value="{{ old('dag_number', $land->dag_number) }}" placeholder="e.g. DAG-101, 102" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>
            </div>

            <!-- Seller Info -->
            <div class="space-y-1.5">
                <label for="seller_information" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Seller Information *</label>
                <input type="text" name="seller_information" id="seller_information" required value="{{ old('seller_information', $land->seller_information) }}" placeholder="e.g. Uttara Land Developers Ltd." class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
            </div>

            <!-- Land Map / Layout Plan Attachment -->
            <div class="space-y-1.5">
                <label for="land_map" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Land Map / Layout Plan (Attachment)</label>
                <input type="file" name="land_map" id="land_map" accept=".pdf,.jpg,.jpeg,.png" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                @if($land->land_map_path)
                    <div class="text-[11px] text-slate-400 mt-1">
                        Current: <a href="{{ asset('storage/' . $land->land_map_path) }}" target="_blank" class="text-indigo-400 hover:text-indigo-300 font-semibold underline">📂 View Current Attachment</a>
                    </div>
                @endif
                <p class="text-[10px] text-slate-500 mt-1">Allowed formats: PDF, JPG, JPEG, PNG (Max size: 5MB). Leave blank to keep current.</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4 pt-4 border-t border-slate-800/80">
                <a href="{{ route('property.lands.index') }}" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-slate-400 hover:text-white font-medium text-sm rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl transition shadow-lg shadow-indigo-600/20 cursor-pointer">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
