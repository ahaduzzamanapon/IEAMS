@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    
    <!-- Top Nav -->
    <div class="flex items-center justify-between">
        <a href="{{ route('vehicles.index') }}" class="text-sm font-medium text-slate-400 hover:text-slate-200 transition">
            ← Back to Fleet Register
        </a>
        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-400">
            {{ ucfirst($vehicle->status) }}
        </span>
    </div>

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 border-b border-slate-800/60 pb-6">
        <div>
            <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">{{ strtoupper($vehicle->vehicle_type) }} FLEET</span>
            <h2 class="text-3xl font-extrabold text-white mt-1">{{ $vehicle->vehicle_number }}</h2>
            <p class="text-slate-400 text-sm mt-1">{{ $vehicle->brand }} {{ $vehicle->model }} (Name: {{ $vehicle->vehicle_name }})</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Details Panels -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Technical specs -->
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-6">
                <h3 class="text-md font-bold text-white uppercase tracking-wider border-b border-slate-850 pb-2">Technical Specifications</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6 text-sm">
                    <div>
                        <div class="text-slate-500 text-xs">Chassis Number</div>
                        <div class="text-white font-medium mt-1 font-mono">{{ $vehicle->chassis_number }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Engine Number</div>
                        <div class="text-white font-medium mt-1 font-mono">{{ $vehicle->engine_number }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Category</div>
                        <div class="text-white font-medium mt-1">{{ $vehicle->vehicle_category }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Manufacturing Year</div>
                        <div class="text-white font-medium mt-1">{{ $vehicle->manufacturing_year ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Color</div>
                        <div class="text-white font-medium mt-1">{{ $vehicle->color }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Seating Capacity</div>
                        <div class="text-white font-medium mt-1">{{ $vehicle->seating_capacity ?? 'N/A' }} seats</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Fuel Tank capacity</div>
                        <div class="text-white font-semibold mt-1">{{ $vehicle->fuel_quantity }} Liters</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Fuel Type</div>
                        <div class="text-white font-medium mt-1">{{ $vehicle->fuel_type }}</div>
                    </div>
                </div>
            </div>

            <!-- Licensing/Fitness specs -->
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-6">
                <h3 class="text-md font-bold text-white uppercase tracking-wider border-b border-slate-850 pb-2">Licensing & Expiration Certificates</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div class="p-4 bg-[#080B11] rounded-xl border {{ $vehicle->registration_expiry_date->isPast() ? 'border-rose-500/50' : ($vehicle->registration_expiry_date->diffInDays(now()) <= 30 ? 'border-amber-500/50' : 'border-slate-800/60') }}">
                        <div class="flex items-center justify-between">
                            <div class="text-xs font-semibold text-slate-400">Registration Status</div>
                            @if($vehicle->registration_expiry_date->isPast())
                                <span class="text-[9px] font-bold text-rose-400 uppercase">⚠ Expired</span>
                            @elseif($vehicle->registration_expiry_date->diffInDays(now()) <= 30)
                                <span class="text-[9px] font-bold text-amber-400 uppercase">⚠ Expiring Soon</span>
                            @else
                                <span class="text-[9px] font-bold text-emerald-400 uppercase">✓ Valid</span>
                            @endif
                        </div>
                        <div class="text-sm font-bold text-white mt-1">Number: {{ $vehicle->registration_number }}</div>
                        <div class="text-xs text-slate-500 mt-2">
                            Valid from {{ $vehicle->registration_date->format('d M, Y') }} to <span class="text-indigo-400">{{ $vehicle->registration_expiry_date->format('d M, Y') }}</span>
                        </div>
                    </div>
                    <div class="p-4 bg-[#080B11] rounded-xl border {{ $vehicle->fitness_expiry_date->isPast() ? 'border-rose-500/50' : ($vehicle->fitness_expiry_date->diffInDays(now()) <= 30 ? 'border-amber-500/50' : 'border-slate-800/60') }}">
                        <div class="flex items-center justify-between">
                            <div class="text-xs font-semibold text-slate-400">Fitness Certificate</div>
                            @if($vehicle->fitness_expiry_date->isPast())
                                <span class="text-[9px] font-bold text-rose-400 uppercase">⚠ Expired</span>
                            @elseif($vehicle->fitness_expiry_date->diffInDays(now()) <= 30)
                                <span class="text-[9px] font-bold text-amber-400 uppercase">⚠ Expiring Soon</span>
                            @else
                                <span class="text-[9px] font-bold text-emerald-400 uppercase">✓ Valid</span>
                            @endif
                        </div>
                        <div class="text-sm font-bold text-white mt-1">Number: {{ $vehicle->fitness_certificate_number }}</div>
                        <div class="text-xs text-slate-500 mt-2">
                            Valid from {{ $vehicle->fitness_issue_date->format('d M, Y') }} to <span class="text-indigo-400">{{ $vehicle->fitness_expiry_date->format('d M, Y') }}</span>
                        </div>
                    </div>

                    @if($vehicle->tax_token_number)
                    <div class="p-4 bg-[#080B11] rounded-xl border {{ $vehicle->tax_token_expiry_date && $vehicle->tax_token_expiry_date->isPast() ? 'border-rose-500/50' : ($vehicle->tax_token_expiry_date && $vehicle->tax_token_expiry_date->diffInDays(now()) <= 30 ? 'border-amber-500/50' : 'border-slate-800/60') }}">
                        <div class="flex items-center justify-between">
                            <div class="text-xs font-semibold text-slate-400">Tax Token</div>
                            @if($vehicle->tax_token_expiry_date && $vehicle->tax_token_expiry_date->isPast())
                                <span class="text-[9px] font-bold text-rose-400 uppercase">⚠ Expired</span>
                            @elseif($vehicle->tax_token_expiry_date && $vehicle->tax_token_expiry_date->diffInDays(now()) <= 30)
                                <span class="text-[9px] font-bold text-amber-400 uppercase">⚠ Expiring Soon</span>
                            @elseif($vehicle->tax_token_expiry_date)
                                <span class="text-[9px] font-bold text-emerald-400 uppercase">✓ Valid</span>
                            @endif
                        </div>
                        <div class="text-sm font-bold text-white mt-1">Number: {{ $vehicle->tax_token_number }}</div>
                        <div class="text-xs text-slate-500 mt-2">
                            @if($vehicle->tax_token_expiry_date)
                                Expires: <span class="text-indigo-400">{{ $vehicle->tax_token_expiry_date->format('d M, Y') }}</span>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($vehicle->insurance_number)
                    <div class="p-4 bg-[#080B11] rounded-xl border {{ $vehicle->insurance_expiry_date && $vehicle->insurance_expiry_date->isPast() ? 'border-rose-500/50' : ($vehicle->insurance_expiry_date && $vehicle->insurance_expiry_date->diffInDays(now()) <= 30 ? 'border-amber-500/50' : 'border-slate-800/60') }}">
                        <div class="flex items-center justify-between">
                            <div class="text-xs font-semibold text-slate-400">Insurance</div>
                            @if($vehicle->insurance_expiry_date && $vehicle->insurance_expiry_date->isPast())
                                <span class="text-[9px] font-bold text-rose-400 uppercase">⚠ Expired</span>
                            @elseif($vehicle->insurance_expiry_date && $vehicle->insurance_expiry_date->diffInDays(now()) <= 30)
                                <span class="text-[9px] font-bold text-amber-400 uppercase">⚠ Expiring Soon</span>
                            @elseif($vehicle->insurance_expiry_date)
                                <span class="text-[9px] font-bold text-emerald-400 uppercase">✓ Valid</span>
                            @endif
                        </div>
                        <div class="text-sm font-bold text-white mt-1">Policy: {{ $vehicle->insurance_number }}</div>
                        <div class="text-xs text-slate-500 mt-2">
                            {{ $vehicle->insurance_company ? 'Company: ' . $vehicle->insurance_company : '' }}
                            @if($vehicle->insurance_expiry_date)
                                | Expires: <span class="text-indigo-400">{{ $vehicle->insurance_expiry_date->format('d M, Y') }}</span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Side: Allocation panels -->
        <div class="space-y-6">
            
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-4">
                <h4 class="text-sm font-bold text-white uppercase tracking-wider">Vehicle Assignment Allocation</h4>
                
                @php
                    $activeAssign = $vehicle->assignments()->where('status', 'active')->first();
                @endphp

                @if($activeAssign)
                    <div class="p-4 rounded-xl bg-indigo-500/5 border border-indigo-500/10 text-xs space-y-3">
                        <div>
                            <span class="text-slate-500 block">Assigned Officer</span>
                            <span class="text-white font-semibold text-sm mt-0.5 block">{{ $activeAssign->officer->name }}</span>
                            <span class="text-slate-400 block mt-0.5">Office: {{ $activeAssign->assigned_office }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block">Assigned Driver</span>
                            <span class="text-white font-semibold block mt-0.5">{{ $activeAssign->driver->name }}</span>
                            <span class="text-slate-400 block mt-0.5">License: {{ $activeAssign->driver->driving_license_number }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block">Assignment Date</span>
                            <span class="text-slate-300">{{ $activeAssign->assignment_date->format('d M, Y') }}</span>
                        </div>
                        <form action="{{ route('vehicles.return', $vehicle->id) }}" method="POST">
                            @csrf
                            <div class="mt-3">
                                <label class="block text-[10px] text-slate-400 mb-1">Return Date</label>
                                <input type="date" name="actual_return_date" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-lg px-2 py-1 text-xs text-white focus:outline-none mb-2">
                                <button type="submit" class="w-full py-2 bg-rose-600 hover:bg-rose-500 text-white font-medium text-xs rounded-lg transition">
                                    Process Return
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <form action="{{ route('vehicles.assign', $vehicle->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-[10px] text-slate-500 mb-1">Select Office</label>
                            <select id="assign_office_id" onchange="loadBranchesForAssign(this.value)" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                                <option value="">Select Office</option>
                                @foreach($offices as $office)
                                    <option value="{{ $office->id }}">{{ $office->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] text-slate-500 mb-1">Select Branch</label>
                            <select id="assign_branch_id" onchange="loadDepartmentsForAssign(this.value)" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                                <option value="">Select Office first</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] text-slate-500 mb-1">Select Department</label>
                            <select id="assign_department_id" onchange="loadUsersForAssign()" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                                <option value="">Select Branch first</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] text-slate-500 mb-1">Select Officer *</label>
                            <select name="assigned_officer_id" id="assign_officer_id" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                                <option value="">Select Officer</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] text-slate-500 mb-1">Select Driver *</label>
                            <select name="assigned_driver_id" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                                <option value="">Select Driver</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->name }} (License: {{ $driver->driving_license_number }})</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="assigned_office" id="assign_office_hidden">
                        <div>
                            <label class="block text-[10px] text-slate-500 mb-1">Assignment Date *</label>
                            <input type="date" name="assignment_date" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                        </div>
                        <div>
                            <textarea name="purpose" rows="2" required placeholder="Purpose of assignment..." class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none"></textarea>
                        </div>
                        <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-xs rounded-xl transition">
                            Assign Vehicle
                        </button>
                    </form>
                @endif
            </div>

        </div>

    </div>

    <!-- History list -->
    <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-xl space-y-4">
        <h3 class="text-md font-bold text-white uppercase tracking-wider border-b border-slate-850 pb-2">Historical Assignment Logs</h3>
        
        <div class="space-y-3">
            @forelse($vehicle->assignments as $assign)
                <div class="p-4 bg-[#080B11] rounded-xl border border-slate-800/60 text-xs flex justify-between items-center">
                    <div>
                        <div class="font-semibold text-white">Assigned to Officer: {{ $assign->officer->name }} | Driver: {{ $assign->driver->name }}</div>
                        <div class="text-slate-500 mt-1">Office: {{ $assign->assigned_office }} | Purpose: {{ $assign->purpose }}</div>
                    </div>
                    <div class="text-right">
                        <span class="px-2 py-0.5 text-[9px] font-bold rounded-full {{ $assign->status === 'active' ? 'bg-indigo-500/10 text-indigo-400' : 'bg-slate-800 text-slate-500' }}">
                            {{ strtoupper($assign->status) }}
                        </span>
                        <div class="text-[10px] text-slate-500 mt-2">
                            {{ $assign->assignment_date->format('d M, Y') }} @if($assign->actual_return_date) - Returned: {{ $assign->actual_return_date->format('d M, Y') }} @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-slate-650 italic text-xs">No historical assignments logged.</div>
            @endforelse
        </div>
    </div>

    <!-- Maintenance Log (SRS 4.2.6) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Add Maintenance Form -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-4 lg:col-span-1">
            <h3 class="text-md font-bold text-white uppercase tracking-wider border-b border-slate-800 pb-2">Log Maintenance</h3>
            <form action="{{ route('vehicles.maintenance.store', $vehicle->id) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Maintenance Type *</label>
                    <select name="maintenance_type" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
                        <option value="repair">Repair</option>
                        <option value="servicing">Servicing</option>
                        <option value="inspection">Inspection</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Maintenance Date *</label>
                    <input type="date" name="maintenance_date" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Workshop / Vendor</label>
                    <input type="text" name="workshop_name" placeholder="Workshop name" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Cost (৳) *</label>
                    <input type="number" step="0.01" name="cost" min="0" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Description *</label>
                    <textarea name="description" required rows="3" placeholder="Describe the maintenance work..." class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none"></textarea>
                </div>
                <button type="submit" class="w-full py-2.5 bg-amber-600 hover:bg-amber-500 text-white font-medium text-xs rounded-xl transition">
                    Log Maintenance Record
                </button>
                <p class="text-[9px] text-slate-600 text-center">BR-08: Vehicle status will be set to "Under Maintenance"</p>
            </form>
        </div>

        <!-- Maintenance History -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                <h3 class="text-md font-bold text-white uppercase tracking-wider">Maintenance History</h3>
                <span class="text-[10px] text-slate-600">BR-10: Records cannot be deleted</span>
            </div>
            <div class="space-y-3">
                @forelse($vehicle->maintenances()->latest()->get() as $maint)
                    <div class="p-4 bg-[#080B11] rounded-xl border border-slate-800/60 text-xs">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2 py-0.5 text-[9px] font-bold rounded-full uppercase {{ $maint->maintenance_type === 'repair' ? 'bg-rose-500/10 text-rose-400' : ($maint->maintenance_type === 'servicing' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-sky-500/10 text-sky-400') }}">
                                        {{ $maint->maintenance_type }}
                                    </span>
                                    <span class="px-2 py-0.5 text-[9px] font-bold rounded-full {{ $maint->status === 'completed' ? 'bg-slate-800 text-slate-500' : 'bg-amber-500/10 text-amber-400' }}">
                                        {{ strtoupper($maint->status) }}
                                    </span>
                                </div>
                                <div class="text-white font-semibold">{{ $maint->description }}</div>
                                @if($maint->workshop_name)
                                    <div class="text-slate-500 mt-0.5">Workshop: {{ $maint->workshop_name }}</div>
                                @endif
                                <div class="text-slate-500 mt-0.5">Date: {{ $maint->maintenance_date->format('d M, Y') }}
                                    @if($maint->completion_date)
                                        → Completed: {{ $maint->completion_date->format('d M, Y') }}
                                    @endif
                                </div>
                            </div>
                            <div class="text-right ml-4">
                                <div class="text-white font-bold">৳{{ number_format($maint->cost, 2) }}</div>
                                @if($maint->status === 'ongoing')
                                    <form action="{{ route('vehicles.maintenance.complete', [$vehicle->id, $maint->id]) }}" method="POST" class="mt-2">
                                        @csrf
                                        <input type="date" name="completion_date" required class="w-full bg-[#080B11] border border-slate-700/50 rounded-lg px-2 py-1 text-[10px] text-white focus:outline-none mb-1">
                                        <button type="submit" class="w-full py-1 bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-[10px] rounded-lg transition">
                                            Mark Complete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-slate-600 italic text-xs text-center py-6">No maintenance records logged for this vehicle.</div>
                @endforelse
            </div>
            @if($vehicle->maintenances()->count() > 0)
                <div class="pt-2 border-t border-slate-800/60 flex justify-between text-xs">
                    <span class="text-slate-500">Total Maintenance Records: {{ $vehicle->maintenances()->count() }}</span>
                    <span class="text-white font-semibold">Total Cost: ৳{{ number_format($vehicle->maintenances()->sum('cost'), 2) }}</span>
                </div>
            @endif
        </div>
    </div>

</div>

<script>
    function loadBranchesForAssign(officeId) {
        const branchSelect = document.getElementById('assign_branch_id');
        const deptSelect = document.getElementById('assign_department_id');
        const userSelect = document.getElementById('assign_officer_id');
        const officeSelect = document.getElementById('assign_office_id');
        
        // Set office name in hidden input
        document.getElementById('assign_office_hidden').value = officeSelect.options[officeSelect.selectedIndex].text;

        branchSelect.innerHTML = '<option value="">Loading...</option>';
        deptSelect.innerHTML = '<option value="">Select Branch first</option>';
        userSelect.innerHTML = '<option value="">Select Officer</option>';

        if (!officeId) {
            branchSelect.innerHTML = '<option value="">Select Office first</option>';
            return;
        }

        fetch(`/api/offices/${officeId}/branches`)
            .then(res => res.json())
            .then(data => {
                branchSelect.innerHTML = '<option value="">Select Branch</option>';
                data.forEach(branch => {
                    const opt = document.createElement('option');
                    opt.value = branch.id;
                    opt.text = `${branch.name} (${branch.code})`;
                    branchSelect.appendChild(opt);
                });
            })
            .catch(() => {
                branchSelect.innerHTML = '<option value="">Failed to load branches</option>';
            });
    }

    function loadDepartmentsForAssign(branchId) {
        const deptSelect = document.getElementById('assign_department_id');
        const userSelect = document.getElementById('assign_officer_id');
        
        deptSelect.innerHTML = '<option value="">Loading...</option>';
        userSelect.innerHTML = '<option value="">Select Officer</option>';

        if (!branchId) {
            deptSelect.innerHTML = '<option value="">Select Branch first</option>';
            return;
        }

        fetch(`/api/branches/${branchId}/departments`)
            .then(res => res.json())
            .then(data => {
                deptSelect.innerHTML = '<option value="">Select Department</option>';
                data.forEach(dept => {
                    const opt = document.createElement('option');
                    opt.value = dept.id;
                    opt.text = `${dept.name} (${dept.code})`;
                    deptSelect.appendChild(opt);
                });
            })
            .catch(() => {
                deptSelect.innerHTML = '<option value="">Failed to load departments</option>';
            });
    }

    function loadUsersForAssign() {
        const officeId = document.getElementById('assign_office_id').value;
        const branchId = document.getElementById('assign_branch_id').value;
        const deptId = document.getElementById('assign_department_id').value;
        const userSelect = document.getElementById('assign_officer_id');

        userSelect.innerHTML = '<option value="">Loading...</option>';

        let url = `/api/users/filter?`;
        if (officeId) url += `office_id=${officeId}&`;
        if (branchId) url += `branch_id=${branchId}&`;
        if (deptId) url += `department_id=${deptId}&`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                userSelect.innerHTML = '<option value="">Select Officer</option>';
                data.forEach(user => {
                    const opt = document.createElement('option');
                    opt.value = user.id;
                    opt.text = `${user.name} (${user.email})`;
                    userSelect.appendChild(opt);
                });
            })
            .catch(() => {
                userSelect.innerHTML = '<option value="">Failed to load officers</option>';
            });
    }
</script>
@endsection
