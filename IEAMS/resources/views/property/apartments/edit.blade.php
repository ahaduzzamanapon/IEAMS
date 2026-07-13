@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">Edit Apartment Details</h2>
            <p class="text-sm text-slate-400 mt-1">Modify flat specifications and occupancy metrics on selected floor level.</p>
        </div>
        <a href="{{ route('property.apartments.index') }}" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 hover:text-white font-medium text-sm rounded-xl transition">
            ← Back to Apartments
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
        <form action="{{ route('property.apartments.update', $apartment->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Project, Land, Plot, Building, Floor dependencies cascading selector -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <div class="space-y-1.5">
                    <label for="project_id" class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Project *</label>
                    <select id="project_id" required onchange="loadLandsForProject(this.value)" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-2.5 py-2 text-[11px] text-white focus:outline-none focus:border-indigo-500 transition">
                        <option value="">Select Project</option>
                        @foreach($projects as $proj)
                            <option value="{{ $proj->id }}" {{ (old('project_id', $selectedProject ? $selectedProject->id : '') == $proj->id) ? 'selected' : '' }}>
                                {{ $proj->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="land_id" class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Land *</label>
                    <select id="land_id" required onchange="loadPlotsForLand(this.value)" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-2.5 py-2 text-[11px] text-white focus:outline-none focus:border-indigo-500 transition">
                        <option value="">Select Land</option>
                        @foreach($lands as $l)
                            <option value="{{ $l->id }}" {{ (old('land_id', $selectedLand ? $selectedLand->id : '') == $l->id) ? 'selected' : '' }}>
                                Khatian: {{ $l->khatian_number }} / Deed: {{ $l->deed_number }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="plot_id" class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Plot *</label>
                    <select id="plot_id" required onchange="loadBuildingsForPlot(this.value)" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-2.5 py-2 text-[11px] text-white focus:outline-none focus:border-indigo-500 transition">
                        <option value="">Select Plot</option>
                        @foreach($plots as $p)
                            <option value="{{ $p->id }}" {{ (old('plot_id', $selectedPlot ? $selectedPlot->id : '') == $p->id) ? 'selected' : '' }}>
                                Plot: {{ $p->plot_number }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="building_id" class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Building *</label>
                    <select id="building_id" required onchange="loadFloorsForBuilding(this.value)" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-2.5 py-2 text-[11px] text-white focus:outline-none focus:border-indigo-500 transition">
                        <option value="">Select Building</option>
                        @foreach($buildings as $b)
                            <option value="{{ $b->id }}" {{ (old('building_id', $selectedBuilding ? $selectedBuilding->id : '') == $b->id) ? 'selected' : '' }}>
                                {{ $b->name }} ({{ $b->number }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="floor_id" class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Floor Level *</label>
                    <select name="floor_id" id="floor_id" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-2.5 py-2 text-[11px] text-white focus:outline-none focus:border-indigo-500 transition">
                        <option value="">Select Floor</option>
                        @foreach($floors as $f)
                            <option value="{{ $f->id }}" {{ (old('floor_id', $apartment->floor_id) == $f->id) ? 'selected' : '' }}>
                                Floor: {{ $f->floor_number }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Apartment Number -->
                <div class="space-y-1.5">
                    <label for="apartment_number" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Apartment Number *</label>
                    <input type="text" name="apartment_number" id="apartment_number" required value="{{ old('apartment_number', $apartment->apartment_number) }}" placeholder="e.g. Apt-101, A-05-03" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <!-- Apartment Name -->
                <div class="space-y-1.5">
                    <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Apartment Name *</label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $apartment->name) }}" placeholder="e.g. Standard Deluxe Suite 101" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Size -->
                <div class="space-y-1.5">
                    <label for="size" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Apartment Size (Sq. Ft.) *</label>
                    <input type="number" step="0.01" name="size" id="size" required value="{{ old('size', $apartment->size) }}" placeholder="e.g. 1250.00" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <!-- Orientation -->
                <div class="space-y-1.5">
                    <label for="orientation" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Orientation *</label>
                    <input type="text" name="orientation" id="orientation" required value="{{ old('orientation', $apartment->orientation) }}" placeholder="e.g. North-East, South, West" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Bedrooms -->
                <div class="space-y-1.5">
                    <label for="bedrooms" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Bedrooms</label>
                    <input type="number" name="bedrooms" id="bedrooms" value="{{ old('bedrooms', $apartment->bedrooms) }}" placeholder="e.g. 3" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <!-- Bathrooms -->
                <div class="space-y-1.5">
                    <label for="bathrooms" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Bathrooms</label>
                    <input type="number" name="bathrooms" id="bathrooms" value="{{ old('bathrooms', $apartment->bathrooms) }}" placeholder="e.g. 2" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <!-- Balcony -->
                <div class="space-y-1.5">
                    <label for="balcony" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Balcony count</label>
                    <input type="number" name="balcony" id="balcony" value="{{ old('balcony', $apartment->balcony) }}" placeholder="e.g. 2" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Parking Spot -->
                <div class="space-y-1.5">
                    <label for="parking" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Parking Spot Code</label>
                    <input type="text" name="parking" id="parking" value="{{ old('parking', $apartment->parking) }}" placeholder="e.g. Spot-101" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <!-- Status -->
                <div class="space-y-1.5">
                    <label for="status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Current Status *</label>
                    <select name="status" id="status" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                        <option value="vacant" {{ old('status', $apartment->status) == 'vacant' ? 'selected' : '' }}>Vacant</option>
                        <option value="reserved" {{ old('status', $apartment->status) == 'reserved' ? 'selected' : '' }}>Reserved</option>
                        <option value="booked" {{ old('status', $apartment->status) == 'booked' ? 'selected' : '' }}>Booked</option>
                        <option value="allocated" {{ old('status', $apartment->status) == 'allocated' ? 'selected' : '' }}>Allocated</option>
                        <option value="rented" {{ old('status', $apartment->status) == 'rented' ? 'selected' : '' }}>Rented</option>
                        <option value="sold" {{ old('status', $apartment->status) == 'sold' ? 'selected' : '' }}>Sold</option>
                        <option value="under_maintenance" {{ old('status', $apartment->status) == 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                        <option value="cancelled" {{ old('status', $apartment->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>

            <!-- Utility Connection Checkbox -->
            <div class="p-4 rounded-xl bg-[#080B11] border border-slate-800/80 flex items-start">
                <div class="flex items-center h-5">
                    <input type="checkbox" name="utility_connection" value="1" id="utility_connection" class="w-4 h-4 text-indigo-600 bg-slate-900 border-slate-700 rounded focus:ring-indigo-500" {{ old('utility_connection', $apartment->utility_connection) ? 'checked' : '' }}>
                </div>
                <div class="ml-3 text-sm">
                    <label for="utility_connection" class="font-medium text-white cursor-pointer select-none">Utility Connection Active</label>
                    <p class="text-slate-500 text-[10px] mt-0.5">Are gas, water, and electricity lines connected?</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4 pt-4 border-t border-slate-800/80">
                <a href="{{ route('property.apartments.index') }}" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-slate-400 hover:text-white font-medium text-sm rounded-xl transition">
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
        const floorSelect = document.getElementById('floor_id');
        landSelect.innerHTML = '<option value="">Loading...</option>';
        plotSelect.innerHTML = '<option value="">Select Land first</option>';
        buildingSelect.innerHTML = '<option value="">Select Plot first</option>';
        floorSelect.innerHTML = '<option value="">Select Bldg first</option>';
        if (!projectId) {
            landSelect.innerHTML = '<option value="">Select Project first</option>';
            return;
        }
        fetch(`/api/projects/${projectId}/lands`)
            .then(res => res.json())
            .then(data => {
                landSelect.innerHTML = '<option value="">Select Land</option>';
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
        const floorSelect = document.getElementById('floor_id');
        plotSelect.innerHTML = '<option value="">Loading...</option>';
        buildingSelect.innerHTML = '<option value="">Select Plot first</option>';
        floorSelect.innerHTML = '<option value="">Select Bldg first</option>';
        if (!landId) {
            plotSelect.innerHTML = '<option value="">Select Land first</option>';
            return;
        }
        fetch(`/api/lands/${landId}/plots`)
            .then(res => res.json())
            .then(data => {
                plotSelect.innerHTML = '<option value="">Select Plot</option>';
                data.forEach(plot => {
                    const opt = document.createElement('option');
                    opt.value = plot.id;
                    opt.text = `Plot: ${plot.plot_number}`;
                    if (selectedPlotId && plot.id == selectedPlotId) {
                        opt.selected = true;
                    }
                    plotSelect.appendChild(opt);
                });
                if (selectedPlotId) {
                    loadBuildingsForPlot(selectedPlotId, "{{ old('building_id', $selectedBuilding ? $selectedBuilding->id : '') }}");
                }
            })
            .catch(() => {
                plotSelect.innerHTML = '<option value="">Failed to load plots</option>';
            });
    }

    function loadBuildingsForPlot(plotId, selectedBuildingId = null) {
        const buildingSelect = document.getElementById('building_id');
        const floorSelect = document.getElementById('floor_id');
        buildingSelect.innerHTML = '<option value="">Loading...</option>';
        floorSelect.innerHTML = '<option value="">Select Bldg first</option>';
        if (!plotId) {
            buildingSelect.innerHTML = '<option value="">Select Plot first</option>';
            return;
        }
        fetch(`/api/plots/${plotId}/buildings`)
            .then(res => res.json())
            .then(data => {
                buildingSelect.innerHTML = '<option value="">Select Building</option>';
                data.forEach(b => {
                    const opt = document.createElement('option');
                    opt.value = b.id;
                    opt.text = `${b.name} (${b.number})`;
                    if (selectedBuildingId && b.id == selectedBuildingId) {
                        opt.selected = true;
                    }
                    buildingSelect.appendChild(opt);
                });
                if (selectedBuildingId) {
                    loadFloorsForBuilding(selectedBuildingId, "{{ old('floor_id', $apartment->floor_id) }}");
                }
            })
            .catch(() => {
                buildingSelect.innerHTML = '<option value="">Failed to load buildings</option>';
            });
    }

    function loadFloorsForBuilding(buildingId, selectedFloorId = null) {
        const floorSelect = document.getElementById('floor_id');
        floorSelect.innerHTML = '<option value="">Loading...</option>';
        if (!buildingId) {
            floorSelect.innerHTML = '<option value="">Select Bldg first</option>';
            return;
        }
        fetch(`/api/buildings/${buildingId}/floors`)
            .then(res => res.json())
            .then(data => {
                floorSelect.innerHTML = '<option value="">Select Floor Level</option>';
                data.forEach(f => {
                    const opt = document.createElement('option');
                    opt.value = f.id;
                    opt.text = `Floor: ${f.floor_number} (${f.floor_name || 'No Name'})`;
                    if (selectedFloorId && f.id == selectedFloorId) {
                        opt.selected = true;
                    }
                    floorSelect.appendChild(opt);
                });
            })
            .catch(() => {
                floorSelect.innerHTML = '<option value="">Failed to load floors</option>';
            });
    }
</script>
@endsection
