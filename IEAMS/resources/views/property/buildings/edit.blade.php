@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">Edit Building Details</h2>
            <p class="text-sm text-slate-400 mt-1">Modify building structure on a selected project plot specification.</p>
        </div>
        <a href="{{ route('property.buildings.index') }}" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 hover:text-white font-medium text-sm rounded-xl transition">
            ← Back to Buildings
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
        <form action="{{ route('property.buildings.update', $building->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Project, Land, and Plot cascading dropdown dependencies -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-1.5">
                    <label for="project_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Housing Project *</label>
                    <select id="project_id" required onchange="loadLandsForProject(this.value)" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition">
                        <option value="">Select Project</option>
                        @foreach($projects as $proj)
                            <option value="{{ $proj->id }}" {{ (old('project_id', $selectedProject ? $selectedProject->id : '') == $proj->id) ? 'selected' : '' }}>
                                {{ $proj->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="land_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Associated Land *</label>
                    <select id="land_id" required onchange="loadPlotsForLand(this.value)" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition">
                        <option value="">Select Land</option>
                        @foreach($lands as $l)
                            <option value="{{ $l->id }}" {{ (old('land_id', $selectedLand ? $selectedLand->id : '') == $l->id) ? 'selected' : '' }}>
                                Khatian: {{ $l->khatian_number }} / Deed: {{ $l->deed_number }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="plot_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Project Plot *</label>
                    <select name="plot_id" id="plot_id" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition">
                        <option value="">Select Plot</option>
                        @foreach($plots as $p)
                            <option value="{{ $p->id }}" {{ (old('plot_id', $building->plot_id) == $p->id) ? 'selected' : '' }}>
                                Plot: {{ $p->plot_number }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Building Name -->
                <div class="space-y-1.5">
                    <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Building Name *</label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $building->name) }}" placeholder="e.g. NHA Heights Tower A" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <!-- Building Number -->
                <div class="space-y-1.5">
                    <label for="number" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Building Number/Code *</label>
                    <input type="text" name="number" id="number" required value="{{ old('number', $building->number) }}" placeholder="e.g. BLDG-01" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Footprint Area -->
                <div class="space-y-1.5">
                    <label for="footprint_area" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Footprint Area (Sq. Ft.) *</label>
                    <input type="number" step="0.01" name="footprint_area" id="footprint_area" required value="{{ old('footprint_area', $building->footprint_area) }}" placeholder="e.g. 3000.00" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <!-- Total Floor -->
                <div class="space-y-1.5">
                    <label for="total_floor" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Floors *</label>
                    <input type="number" name="total_floor" id="total_floor" required value="{{ old('total_floor', $building->total_floor) }}" placeholder="e.g. 10" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>
            </div>

            <!-- Amenities -->
            <div class="space-y-3">
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Amenities Available</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Lift -->
                    <div class="p-4 rounded-xl bg-[#080B11] border border-slate-800/80 flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="has_lift" value="1" id="has_lift" class="w-4 h-4 text-indigo-600 bg-slate-900 border-slate-700 rounded focus:ring-indigo-500" {{ old('has_lift', $building->has_lift) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="has_lift" class="font-medium text-white cursor-pointer select-none">Has Lift System</label>
                            <p class="text-slate-500 text-[10px] mt-0.5">Is a lift/elevator installed in this building?</p>
                        </div>
                    </div>

                    <!-- Parking -->
                    <div class="p-4 rounded-xl bg-[#080B11] border border-slate-800/80 flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="has_parking" value="1" id="has_parking" class="w-4 h-4 text-indigo-600 bg-slate-900 border-slate-700 rounded focus:ring-indigo-500" {{ old('has_parking', $building->has_parking) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="has_parking" class="font-medium text-white cursor-pointer select-none">Has Car Parking</label>
                            <p class="text-slate-500 text-[10px] mt-0.5">Is basement or ground level parking available?</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Construction Status -->
            <div class="space-y-1.5">
                <label for="construction_status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Construction Status *</label>
                <select name="construction_status" id="construction_status" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                    <option value="planned" {{ old('construction_status', $building->construction_status) == 'planned' ? 'selected' : '' }}>Planned</option>
                    <option value="under_construction" {{ old('construction_status', $building->construction_status) == 'under_construction' ? 'selected' : '' }}>Under Construction</option>
                    <option value="completed" {{ old('construction_status', $building->construction_status) == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4 pt-4 border-t border-slate-800/80">
                <a href="{{ route('property.buildings.index') }}" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-slate-400 hover:text-white font-medium text-sm rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl transition shadow-lg shadow-indigo-600/20 cursor-pointer">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function loadLandsForProject(projectId, selectedLandId = null) {
        const landSelect = document.getElementById('land_id');
        const plotSelect = document.getElementById('plot_id');
        landSelect.innerHTML = '<option value="">Loading Lands...</option>';
        plotSelect.innerHTML = '<option value="">Select Land first</option>';
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
                if (selectedLandId) {
                    loadPlotsForLand(selectedLandId, "{{ old('plot_id', $building->plot_id) }}");
                }
            })
            .catch(() => {
                landSelect.innerHTML = '<option value="">Failed to load lands</option>';
            });
    }

    function loadPlotsForLand(landId, selectedPlotId = null) {
        const plotSelect = document.getElementById('plot_id');
        plotSelect.innerHTML = '<option value="">Loading Plots...</option>';
        if (!landId) {
            plotSelect.innerHTML = '<option value="">Select Land first</option>';
            return;
        }
        fetch(`/api/lands/${landId}/plots`)
            .then(res => res.json())
            .then(data => {
                plotSelect.innerHTML = '<option value="">Select Target Plot</option>';
                data.forEach(plot => {
                    const opt = document.createElement('option');
                    opt.value = plot.id;
                    opt.text = `Plot: ${plot.plot_number} (${plot.plot_name || 'No Name'})`;
                    if (selectedPlotId && plot.id == selectedPlotId) {
                        opt.selected = true;
                    }
                    plotSelect.appendChild(opt);
                });
            })
            .catch(() => {
                plotSelect.innerHTML = '<option value="">Failed to load plots</option>';
            });
    }
</script>
@endsection
