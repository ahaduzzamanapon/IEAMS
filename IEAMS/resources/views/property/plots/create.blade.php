@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">Create Project Plot</h2>
            <p class="text-sm text-slate-400 mt-1">Register a new plot under an NHA project land specification.</p>
        </div>
        <a href="{{ route('property.plots.index') }}" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 hover:text-white font-medium text-sm rounded-xl transition">
            ← Back to Plots
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
        <form action="{{ route('property.plots.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Project & Land Dependencies -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label for="project_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Housing Project *</label>
                    <select name="project_id" id="project_id" required onchange="loadLandsForProject(this.value)" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                        <option value="">Select Parent Project</option>
                        @foreach($projects as $proj)
                            <option value="{{ $proj->id }}" {{ old('project_id') == $proj->id ? 'selected' : '' }}>
                                {{ $proj->name }}
                             </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="land_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Associated Land Deed *</label>
                    <select name="land_id" id="land_id" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                        <option value="">Select Project first</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Plot Number -->
                <div class="space-y-1.5">
                    <label for="plot_number" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Plot Number *</label>
                    <input type="text" name="plot_number" id="plot_number" required value="{{ old('plot_number') }}" placeholder="e.g. PLOT-01A" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <!-- Plot Name -->
                <div class="space-y-1.5">
                    <label for="plot_name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Plot Name</label>
                    <input type="text" name="plot_name" id="plot_name" value="{{ old('plot_name') }}" placeholder="e.g. Premium Residential Plot A" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Plot Area -->
                <div class="space-y-1.5">
                    <label for="plot_area" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Plot Area (Sq. Ft.) *</label>
                    <input type="number" step="0.01" name="plot_area" id="plot_area" required value="{{ old('plot_area') }}" placeholder="e.g. 3600.00" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <!-- Status -->
                <div class="space-y-1.5">
                    <label for="status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Initial Status *</label>
                    <select name="status" id="status" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                        <option value="vacant" {{ old('status') == 'vacant' ? 'selected' : '' }}>Vacant</option>
                        <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                        <option value="leased" {{ old('status') == 'leased' ? 'selected' : '' }}>Leased</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4 pt-4 border-t border-slate-800/80">
                <a href="{{ route('property.plots.index') }}" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-slate-400 hover:text-white font-medium text-sm rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl transition shadow-lg shadow-indigo-600/20 cursor-pointer">
                    Save Plot
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function loadLandsForProject(projectId, selectedLandId = null) {
        const landSelect = document.getElementById('land_id');
        landSelect.innerHTML = '<option value="">Loading Lands...</option>';
        if (!projectId) {
            landSelect.innerHTML = '<option value="">Select Project first</option>';
            return;
        }
        fetch(`/api/projects/${projectId}/lands`)
            .then(res => res.json())
            .then(data => {
                landSelect.innerHTML = '<option value="">Select Associated Land</option>';
                data.forEach(land => {
                    const opt = document.createElement('option');
                    opt.value = land.id;
                    opt.text = `Khatian: ${land.khatian_number} / Deed: ${land.deed_number}`;
                    if (selectedLandId && land.id == selectedLandId) {
                        opt.selected = true;
                    }
                    landSelect.appendChild(opt);
                });
            })
            .catch(() => {
                landSelect.innerHTML = '<option value="">Failed to load lands</option>';
            });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const projVal = document.getElementById('project_id').value;
        if (projVal) {
            loadLandsForProject(projVal, "{{ old('land_id') }}");
        }
    });
</script>
@endsection
