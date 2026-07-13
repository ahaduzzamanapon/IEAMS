@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-bold tracking-tight text-white">Project Registry</h2>
        <p class="text-sm text-slate-400 mt-1">Manage National Housing Authority (NHA) housing projects and estate sites.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Create Project Form -->
        <div class="p-6 rounded-2xl bg-[#0E132F]/80 border border-slate-800/80 space-y-6 lg:col-span-1">
            <h3 class="text-lg font-bold text-white">Create Housing Project</h3>
            <form action="{{ route('property.store-project') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Project ID Code *</label>
                    <input type="text" name="project_id_code" required placeholder="e.g. NHA-PRJ-001" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Project Code *</label>
                    <input type="text" name="project_code" required placeholder="e.g. PRJ001" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Project Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Uttara Flat Project" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Division *</label>
                        <select name="division" id="project_division" required onchange="loadDistricts(this.value, 'project_district', 'project_upazila')" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                            <option value="">Select Division</option>
                            @foreach($divisions as $div)
                                <option value="{{ $div->name }}" data-id="{{ $div->id }}">{{ $div->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">District *</label>
                        <select name="district" id="project_district" required onchange="loadUpazilas(this.value, 'project_upazila')" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                            <option value="">Select District</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Upazila *</label>
                        <select name="upazila" id="project_upazila" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                            <option value="">Select Upazila</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Mouza *</label>
                        <input type="text" name="mouza" required placeholder="Mouza 21, 23" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Total Land (Acres) *</label>
                        <input type="number" step="0.01" name="total_land" required placeholder="5.5" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Total Road Land *</label>
                        <input type="number" step="0.01" name="total_road_land" required placeholder="1.2" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Planned Plots *</label>
                        <input type="number" name="total_planned_plot" required placeholder="20" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Planned Apts *</label>
                        <input type="number" name="total_planned_apartment" required placeholder="100" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Start Date *</label>
                        <input type="date" name="project_start_date" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Expected End Date</label>
                        <input type="date" name="expected_completion_date" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Cost (৳)</label>
                        <input type="number" step="0.01" name="estimated_project_cost" placeholder="Estimated cost" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Description</label>
                        <input type="text" name="description" placeholder="Short description" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                </div>
                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition">
                    Save Project
                </button>
            </form>
        </div>

        <!-- Project List -->
        <div class="p-6 rounded-2xl bg-[#0E132F]/80 border border-slate-800/80 lg:col-span-2">
            <h3 class="text-lg font-bold text-white mb-6">Registered Housing Sites</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-900/60 text-slate-400 text-xs uppercase font-medium">
                        <tr>
                            <th class="px-6 py-4">Project ID & Name</th>
                            <th class="px-6 py-4">Location</th>
                            <th class="px-6 py-4">Land Info</th>
                            <th class="px-6 py-4">Plots/Apts</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($projects as $proj)
                            <tr class="hover:bg-slate-800/20">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-white">{{ $proj->name }}</div>
                                    <div class="text-xs text-slate-500 font-mono">{{ $proj->project_id_code }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    {{ $proj->upazila }}, {{ $proj->district }}
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <div>Total: {{ $proj->total_land }} acres</div>
                                    <div class="text-slate-500">Road: {{ $proj->total_road_land }} acres</div>
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <div>Plots: {{ $proj->total_planned_plot }}</div>
                                    <div class="text-slate-500">Apts: {{ $proj->total_planned_apartment }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $proj->status === 'ongoing' ? 'bg-indigo-500/10 text-indigo-400' : ($proj->status === 'completed' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-amber-500/10 text-amber-400') }}">
                                        {{ ucfirst($proj->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <a href="{{ route('property.project-show', $proj->id) }}" class="px-2.5 py-1.5 bg-indigo-600/10 hover:bg-indigo-600 text-indigo-400 hover:text-white font-semibold text-xs rounded-lg transition mr-1 cursor-pointer animate-fade-in">
                                        View
                                    </a>
                                    <button type="button" onclick="showEditProjectModal({{ json_encode($proj) }})" class="px-2.5 py-1.5 bg-amber-600/20 hover:bg-amber-600/30 text-amber-400 font-semibold text-xs rounded-lg transition mr-1 cursor-pointer">
                                        Edit
                                    </button>
                                    <form action="{{ route('property.destroy-project', $proj->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project along with all associated lands, plots, buildings, floors, and apartments? This action cannot be undone.');" class="inline m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 bg-rose-600/20 hover:bg-rose-600/30 text-rose-400 font-semibold text-xs rounded-lg transition cursor-pointer">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-500">No project sites registered. Create a project on the left.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $projects->links() }}
            </div>
        </div>

    </div>
</div>

<!-- Edit Project Modal -->
<div id="editProjectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden px-4">
    <div class="bg-[#0E132F] border border-slate-800 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-2">
            <h3 class="text-md font-bold text-white uppercase tracking-wider">Edit Housing Project</h3>
            <button type="button" onclick="closeEditProjectModal()" class="text-slate-400 hover:text-slate-200 cursor-pointer">✕</button>
        </div>
        
        <form id="editProjectForm" method="POST" class="space-y-3 text-sm text-slate-350">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Project ID Code *</label>
                    <input type="text" id="edit_project_id_code" name="project_id_code" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Project Code *</label>
                    <input type="text" id="edit_project_code" name="project_code" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Project Name *</label>
                <input type="text" id="edit_name" name="name" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-indigo-500">
            </div>

             <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Division *</label>
                    <select id="edit_division" name="division" required onchange="loadDistricts(this.value, 'edit_district', 'edit_upazila')" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-indigo-500">
                        <option value="">Select Division</option>
                        @foreach($divisions as $div)
                            <option value="{{ $div->name }}" data-id="{{ $div->id }}">{{ $div->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">District *</label>
                    <select id="edit_district" name="district" required onchange="loadUpazilas(this.value, 'edit_upazila')" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-indigo-500">
                        <option value="">Select District</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Upazila *</label>
                    <select id="edit_upazila" name="upazila" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-indigo-500">
                        <option value="">Select Upazila</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Mouza *</label>
                    <input type="text" id="edit_mouza" name="mouza" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Total Land (Acres) *</label>
                    <input type="number" step="0.01" id="edit_total_land" name="total_land" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Total Road Land *</label>
                    <input type="number" step="0.01" id="edit_total_road_land" name="total_road_land" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Planned Plots *</label>
                    <input type="number" id="edit_total_planned_plot" name="total_planned_plot" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Planned Apts *</label>
                    <input type="number" id="edit_total_planned_apartment" name="total_planned_apartment" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Status *</label>
                    <select id="edit_status" name="status" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none">
                        <option value="planning">Planning</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="completed">Completed</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Estimated Cost (৳)</label>
                    <input type="number" step="0.01" id="edit_estimated_project_cost" name="estimated_project_cost" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Start Date *</label>
                    <input type="date" id="edit_project_start_date" name="project_start_date" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Expected End Date</label>
                    <input type="date" id="edit_expected_completion_date" name="expected_completion_date" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Project Description</label>
                <textarea id="edit_description" name="description" rows="2" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none"></textarea>
            </div>

            <div class="flex items-center gap-3 pt-2 justify-end">
                <button type="button" onclick="closeEditProjectModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-705 text-slate-300 font-semibold text-xs rounded-xl transition cursor-pointer">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs rounded-xl transition cursor-pointer">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    async function loadDistricts(divisionName, districtSelectId, upazilaSelectId, selectedDistrictName = '') {
        // Find division id from options based on divisionName
        const isEdit = districtSelectId.startsWith('edit_');
        const divisionSelect = isEdit ? document.getElementById('edit_division') : document.getElementById('project_division');
        
        let divisionId = '';
        for (let opt of divisionSelect.options) {
            if (opt.value === divisionName) {
                divisionId = opt.getAttribute('data-id');
                break;
            }
        }
        
        const districtSelect = document.getElementById(districtSelectId);
        const upazilaSelect = document.getElementById(upazilaSelectId);
        
        districtSelect.innerHTML = '<option value="">Select District</option>';
        upazilaSelect.innerHTML = '<option value="">Select Upazila</option>';
        
        if (!divisionId) return;
        
        try {
            const res = await fetch(`/api/geocode/divisions/${divisionId}/districts`);
            const districts = await res.json();
            districts.forEach(dist => {
                const opt = document.createElement('option');
                opt.value = dist.name;
                opt.textContent = dist.name;
                opt.setAttribute('data-id', dist.id);
                if (selectedDistrictName && dist.name === selectedDistrictName) {
                    opt.selected = true;
                }
                districtSelect.appendChild(opt);
            });
        } catch (err) {
            console.error('Error loading districts:', err);
        }
    }

    async function loadUpazilas(districtName, upazilaSelectId, selectedUpazilaName = '') {
        const isEdit = upazilaSelectId.startsWith('edit_');
        const districtSelect = isEdit ? document.getElementById('edit_district') : document.getElementById('project_district');
        
        let districtId = '';
        for (let opt of districtSelect.options) {
            if (opt.value === districtName) {
                districtId = opt.getAttribute('data-id');
                break;
            }
        }
        
        const upazilaSelect = document.getElementById(upazilaSelectId);
        upazilaSelect.innerHTML = '<option value="">Select Upazila</option>';
        
        if (!districtId) return;
        
        try {
            const res = await fetch(`/api/geocode/districts/${districtId}/upazilas`);
            const upazilas = await res.json();
            upazilas.forEach(up => {
                const opt = document.createElement('option');
                opt.value = up.name;
                opt.textContent = up.name;
                opt.setAttribute('data-id', up.id);
                if (selectedUpazilaName && up.name === selectedUpazilaName) {
                    opt.selected = true;
                }
                upazilaSelect.appendChild(opt);
            });
        } catch (err) {
            console.error('Error loading upazilas:', err);
        }
    }

    async function showEditProjectModal(project) {
        document.getElementById('editProjectForm').action = `/property/projects/${project.id}`;
        
        document.getElementById('edit_project_id_code').value = project.project_id_code;
        document.getElementById('edit_project_code').value = project.project_code;
        document.getElementById('edit_name').value = project.name;
        document.getElementById('edit_mouza').value = project.mouza;
        document.getElementById('edit_total_land').value = project.total_land;
        document.getElementById('edit_total_road_land').value = project.total_road_land;
        document.getElementById('edit_total_planned_plot').value = project.total_planned_plot;
        document.getElementById('edit_total_planned_apartment').value = project.total_planned_apartment;
        document.getElementById('edit_status').value = project.status;
        
        if (project.project_start_date) {
            document.getElementById('edit_project_start_date').value = project.project_start_date.substring(0, 10);
        }
        
        if (project.expected_completion_date) {
            document.getElementById('edit_expected_completion_date').value = project.expected_completion_date.substring(0, 10);
        } else {
            document.getElementById('edit_expected_completion_date').value = '';
        }
        
        document.getElementById('edit_description').value = project.description || '';
        
        document.getElementById('edit_estimated_project_cost').value = project.estimated_project_cost || '';
        
        // Populate Division and load others asynchronously
        const editDivSelect = document.getElementById('edit_division');
        editDivSelect.value = project.division;
        
        // Wait for districts and select
        await loadDistricts(project.division, 'edit_district', 'edit_upazila', project.district);
        
        // Wait for upazilas and select
        await loadUpazilas(project.district, 'edit_upazila', project.upazila);
        
        document.getElementById('editProjectModal').classList.remove('hidden');
    }

    function closeEditProjectModal() {
        document.getElementById('editProjectModal').classList.add('hidden');
    }
</script>
@endsection
