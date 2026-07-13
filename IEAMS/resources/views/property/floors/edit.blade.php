@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">Edit Floor Details</h2>
            <p class="text-sm text-slate-400 mt-1">Modify building floor details along with its maximum planned apartment capacity.</p>
        </div>
        <a href="{{ route('property.floors.index') }}" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 hover:text-white font-medium text-sm rounded-xl transition">
            ← Back to Floors
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
        <form action="{{ route('property.floors.update', $floor->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Project, Land, Plot, Building dependencies cascading selector -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="space-y-1.5">
                    <label for="project_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Project *</label>
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
                    <label for="land_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Land *</label>
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
                    <label for="plot_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Plot *</label>
                    <select id="plot_id" required onchange="loadBuildingsForPlot(this.value)" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition">
                        <option value="">Select Plot</option>
                        @foreach($plots as $p)
                            <option value="{{ $p->id }}" {{ (old('plot_id', $selectedPlot ? $selectedPlot->id : '') == $p->id) ? 'selected' : '' }}>
                                Plot: {{ $p->plot_number }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="building_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Building *</label>
                    <select name="building_id" id="building_id" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition">
                        <option value="">Select Building</option>
                        @foreach($buildings as $b)
                            <option value="{{ $b->id }}" {{ (old('building_id', $floor->building_id) == $b->id) ? 'selected' : '' }}>
                                {{ $b->name }} ({{ $b->number }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Floor Number -->
                <div class="space-y-1.5">
                    <label for="floor_number" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Floor Number *</label>
                    <input type="text" name="floor_number" id="floor_number" required value="{{ old('floor_number', $floor->floor_number) }}" placeholder="e.g. Ground Floor, 1st Floor, 0, 1" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <!-- Floor Name -->
                <div class="space-y-1.5">
                    <label for="floor_name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Floor Name/Alias</label>
                    <input type="text" name="floor_name" id="floor_name" value="{{ old('floor_name', $floor->floor_name) }}" placeholder="e.g. Floor 1" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>
            </div>

            <!-- Total Apartment Planned -->
            <div class="space-y-1.5">
                <label for="total_apartment" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Apartment Capacity (Planned Count) *</label>
                <input type="number" name="total_apartment" id="total_apartment" required value="{{ old('total_apartment', $floor->total_apartment) }}" placeholder="e.g. 4" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4 pt-4 border-t border-slate-800/80">
                <a href="{{ route('property.floors.index') }}" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-slate-400 hover:text-white font-medium text-sm rounded-xl transition">
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
        const buildingSelect = document.getElementById('building_id');
        landSelect.innerHTML = '<option value="">Loading Lands...</option>';
        plotSelect.innerHTML = '<option value="">Select Land first</option>';
        buildingSelect.innerHTML = '<option value="">Select Plot first</option>';
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
                    loadPlotsForLand(selectedLandId, "{{ old('plot_id', $selectedPlot ? $selectedPlot->id : '') }}");
                }
            })
            .catch(() => {
                landSelect.innerHTML = '<option value="">Failed to load lands</option>';
            });
    }

    function loadPlotsForLand(landId, selectedPlotId = null) {
        const plotSelect = document.getElementById('plot_id');
        const buildingSelect = document.getElementById('building_id');
        plotSelect.innerHTML = '<option value="">Loading Plots...</option>';
        buildingSelect.innerHTML = '<option value="">Select Plot first</option>';
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
                if (selectedPlotId) {
                    loadBuildingsForPlot(selectedPlotId, "{{ old('building_id', $floor->building_id) }}");
                }
            })
            .catch(() => {
                plotSelect.innerHTML = '<option value="">Failed to load plots</option>';
            });
    }

    function loadBuildingsForPlot(plotId, selectedBuildingId = null) {
        const buildingSelect = document.getElementById('building_id');
        buildingSelect.innerHTML = '<option value="">Loading Buildings...</option>';
        if (!plotId) {
            buildingSelect.innerHTML = '<option value="">Select Plot first</option>';
            return;
        }
        fetch(`/api/plots/${plotId}/buildings`)
            .then(res => res.json())
            .then(data => {
                buildingSelect.innerHTML = '<option value="">Select Target Building</option>';
                data.forEach(b => {
                    const opt = document.createElement('option');
                    opt.value = b.id;
                    opt.text = `${b.name} (${b.number})`;
                    if (selectedBuildingId && b.id == selectedBuildingId) {
                        opt.selected = true;
                    }
                    buildingSelect.appendChild(opt);
                });
            })
            .catch(() => {
                buildingSelect.innerHTML = '<option value="">Failed to load buildings</option>';
            });
    }
</script>
@endsection
