@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    
    <!-- Top Bar Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('assets.index') }}" class="text-sm font-medium text-slate-400 hover:text-slate-200 transition">
            ← Back to Assets Register
        </a>
        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $asset->maintenance_status === 'available' ? 'bg-emerald-500/10 text-emerald-400' : ($asset->maintenance_status === 'assigned' ? 'bg-indigo-500/10 text-indigo-400' : 'bg-rose-500/10 text-rose-400') }}">
            {{ ucfirst(str_replace('_', ' ', $asset->maintenance_status)) }}
        </span>
    </div>

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 border-b border-slate-800/60 pb-6">
        <div>
            @if($asset->unique_asset_id)
                <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">{{ strtoupper($asset->asset_type) }} ASSET</span>
                <h2 class="text-3xl font-extrabold text-white tracking-wider mt-1">{{ $asset->unique_asset_id }}</h2>
                <p class="text-slate-400 text-sm mt-1">{{ $asset->brand }} - {{ $asset->model }} (Serial: {{ $asset->serial_number }})</p>
            @else
                <span class="text-xs font-bold text-amber-400 uppercase tracking-widest">CONSUMER REGISTRY</span>
                <h2 class="text-3xl font-extrabold text-white mt-1">{{ $asset->category->name }}</h2>
                <p class="text-slate-400 text-sm mt-1">Quantity Available: {{ $asset->quantity }}</p>
            @endif
        </div>
        
        @if($asset->asset_type !== 'consumer')
            <div class="flex items-center gap-4 bg-[#0E1325]/85 border border-slate-800/80 p-3 rounded-2xl shadow-xl">
                <div id="qrcode" class="p-1 bg-white rounded-lg"></div>
                <div class="space-y-1">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Asset Tag QR</div>
                    <div class="text-xs font-extrabold text-white font-mono">{{ $asset->unique_asset_id }}</div>
                    <p class="text-[9px] text-slate-500">Scan for specifications</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Asset Detail Panels -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Basic & Procurement Info -->
        <div class="lg:col-span-2 space-y-8">
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-6">
                <h3 class="text-md font-bold text-white uppercase tracking-wider border-b border-slate-850 pb-2">Technical & Procurement Specifications</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6 text-sm">
                    <div>
                        <div class="text-slate-500 text-xs">Asset Category</div>
                        <div class="text-white font-medium mt-1">{{ $asset->category->name }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Sub-Category</div>
                        <div class="text-white font-medium mt-1">{{ $asset->subCategory->name }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Acquisition Date</div>
                        <div class="text-white font-medium mt-1">{{ $asset->purchase_date ? $asset->purchase_date->format('d M, Y') : 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Purchase Cost</div>
                        <div class="text-white font-semibold mt-1">৳{{ $asset->purchase_cost ? number_format($asset->purchase_cost, 2) : '0.00' }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Capitalized Cost</div>
                        <div class="text-white font-semibold mt-1">৳{{ $asset->capitalized_cost ? number_format($asset->capitalized_cost, 2) : '0.00' }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Total Registered Cost</div>
                        <div class="text-white font-bold mt-1">৳{{ $asset->total_cost ? number_format($asset->total_cost, 2) : '0.00' }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">PO Number</div>
                        <div class="text-white font-medium mt-1">{{ $asset->purchase_order_number ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Invoice Number</div>
                        <div class="text-white font-medium mt-1">{{ $asset->invoice_number ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Sourcing Vendor</div>
                        <div class="text-indigo-400 font-semibold mt-1">{{ $asset->vendor ? $asset->vendor->name : 'N/A' }}</div>
                    </div>
                </div>

                @if($asset->warranty_applicable)
                    <div class="mt-6 p-4 rounded-xl bg-violet-500/5 border border-violet-500/10 flex items-center justify-between">
                        <div>
                            <div class="text-xs text-violet-400 font-semibold">Active Warranty Coverage</div>
                            <div class="text-xs text-slate-400 mt-1">
                                Coverage from <span class="text-slate-200">{{ $asset->warranty_start_date->format('d M, Y') }}</span> to <span class="text-slate-200">{{ $asset->warranty_end_date->format('d M, Y') }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-2.5 py-1 bg-violet-500/10 text-violet-400 text-[10px] font-bold rounded-full">
                                {{ now()->diffInDays($asset->warranty_end_date, false) > 0 ? round(now()->diffInDays($asset->warranty_end_date, false)) . ' days left' : 'Expired' }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Depreciation status card -->
            @if($asset->asset_type !== 'consumer')
                <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-6">
                    <h3 class="text-md font-bold text-white uppercase tracking-wider border-b border-slate-850 pb-2">Depreciation Valuation</h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm">
                        <div>
                            <div class="text-slate-500 text-xs">Depreciation Method</div>
                            <div class="text-white font-medium mt-1">{{ $asset->depreciation_method === 'straight-line' ? 'Straight-Line' : 'Written Down Value' }}</div>
                        </div>
                        <div>
                            <div class="text-slate-500 text-xs">Useful Life</div>
                            <div class="text-white font-medium mt-1">{{ $asset->useful_life }} Years</div>
                        </div>
                        <div>
                            <div class="text-slate-500 text-xs">Salvage Value (Amount)</div>
                            <div class="text-white font-semibold mt-1">৳{{ number_format($asset->salvage_value_amount, 2) }} ({{ $asset->salvage_value_percentage }}%)</div>
                        </div>
                        <div>
                            <div class="text-slate-500 text-xs text-indigo-400 font-bold">Current Book Value</div>
                            <div class="text-indigo-400 font-extrabold mt-1">৳{{ number_format($asset->current_book_value, 2) }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Management Actions Panel -->
        <div class="space-y-6">
            
            <!-- 1. Assignment Action -->
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-4">
                <h4 class="text-sm font-bold text-white uppercase tracking-wider">Custodian Allocation</h4>
                
                @php
                    $activeAssignment = $asset->assignments()->where('status', 'active')->first();
                @endphp

                @if($activeAssignment)
                    <div class="p-4 rounded-xl bg-indigo-500/5 border border-indigo-500/10 text-xs space-y-3">
                        <div>
                            <span class="text-slate-500 block">Current Custodian</span>
                            <span class="text-white font-semibold mt-0.5 block text-sm">{{ $activeAssignment->custodian->name }}</span>
                            <span class="text-slate-400 block mt-0.5">{{ $activeAssignment->assigned_office }} office</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block">Assigned Date</span>
                            <span class="text-slate-300">{{ $activeAssignment->assigned_date->format('d M, Y') }}</span>
                        </div>
                        <form action="{{ route('assets.return', $asset->id) }}" method="POST">
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

                    <!-- Transfer form if assigned -->
                    <div class="border-t border-slate-800/60 pt-4">
                        <span class="text-xs font-semibold text-slate-300 block mb-2">Transfer Custodian</span>
                        <form action="{{ route('assets.transfer', $asset->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-[10px] text-slate-500 mb-1">Select Target Office</label>
                                <select id="transfer_office_id" name="to_office_id" required onchange="loadBranchesForTransfer(this.value)" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                                    <option value="">Select Target Office</option>
                                    @foreach($offices as $office)
                                        <option value="{{ $office->id }}">{{ $office->name }} ({{ $office->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500 mb-1">Select Target Branch</label>
                                <select id="transfer_branch_id" name="to_branch_id" onchange="loadDepartmentsForTransfer(this.value)" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                                    <option value="">Select Target Office first</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500 mb-1">Select Target Department</label>
                                <select id="transfer_department_id" onchange="loadUsersForTransfer()" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                                    <option value="">Select Target Branch first</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500 mb-1">Select New Custodian *</label>
                                <select name="to_custodian_id" id="transfer_custodian_id" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                                    <option value="">Select New Custodian</option>
                                </select>
                            </div>
                            <div>
                                <input type="date" name="transfer_date" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                            </div>
                            <div>
                                <input type="text" name="remarks" placeholder="Transfer Remarks (Optional)" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                            </div>
                            <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-xs rounded-xl transition">
                                Transfer Asset
                            </button>
                        </form>
                    </div>

                @else
                    <form action="{{ route('assets.assign', $asset->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-[10px] text-slate-500 mb-1">Select Office</label>
                            <select id="assign_office_id" name="office_id" required onchange="loadBranchesForAssetAssign(this.value)" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                                <option value="">Select Office</option>
                                @foreach($offices as $office)
                                    <option value="{{ $office->id }}">{{ $office->name }} ({{ $office->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] text-slate-500 mb-1">Select Branch</label>
                            <select id="assign_branch_id" name="branch_id" onchange="loadDepartmentsForAssetAssign(this.value)" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                                <option value="">Select Office first</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] text-slate-500 mb-1">Select Department</label>
                            <select id="assign_department_id" onchange="loadUsersForAssetAssign()" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                                <option value="">Select Branch first</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] text-slate-500 mb-1">Select Custodian *</label>
                            <select name="custodian_id" id="assign_custodian_id" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                                <option value="">Select Custodian</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] text-slate-500 mb-1">Assignment Date</label>
                            <input type="date" name="assigned_date" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                        </div>
                        <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-xs rounded-xl transition">
                            Assign Asset
                        </button>
                    </form>
                @endif
            </div>

            <!-- 2. Maintenance Action -->
            @if($asset->asset_type !== 'consumer')
                <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-4">
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider">Maintenance & Servicing</h4>
                    
                    @php
                        $activeMaintenance = $asset->maintenances()->where('status', 'pending')->orWhere('status', 'ongoing')->first();
                    @endphp

                    @if($activeMaintenance)
                        <div class="p-4 rounded-xl bg-rose-500/5 border border-rose-500/10 text-xs space-y-3">
                            <div>
                                <span class="text-slate-500 block">Maintenance Type</span>
                                <span class="text-white font-semibold mt-0.5 block text-sm">{{ ucfirst($activeMaintenance->maintenance_type) }}</span>
                                <span class="text-slate-400 block mt-0.5">{{ $activeMaintenance->description }}</span>
                            </div>
                            <div>
                                <span class="text-slate-500 block">Logged Cost</span>
                                <span class="text-white font-bold">৳{{ number_format($activeMaintenance->cost, 2) }}</span>
                            </div>
                            <form action="{{ route('assets.complete-maintenance', [$asset->id, $activeMaintenance->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-xs rounded-lg transition">
                                    Mark as Completed
                                </button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('assets.maintenance', $asset->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <select name="maintenance_type" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                                    <option value="repair">Repair</option>
                                    <option value="servicing">Servicing</option>
                                </select>
                            </div>
                            <div>
                                <input type="number" step="0.01" name="cost" required placeholder="Estimated Cost (৳)" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                            </div>
                            <div>
                                <input type="date" name="maintenance_date" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none">
                            </div>
                            <div>
                                <textarea name="description" rows="2" placeholder="Description..." class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:outline-none"></textarea>
                            </div>
                            <button type="submit" class="w-full py-2 bg-amber-600 hover:bg-amber-500 text-white font-medium text-xs rounded-xl transition">
                                Log Maintenance
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>

    </div>

    <!-- History Tabs -->
    <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-xl space-y-6">
        <h3 class="text-md font-bold text-white uppercase tracking-wider border-b border-slate-850 pb-2">Historical Audit Trail Logs</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
            
            <!-- Assignments & Transfers Logs -->
            <div class="space-y-4">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Assignment & Transfers History</h4>
                <div class="space-y-3">
                    @forelse($asset->assignments as $assign)
                        <div class="p-3 bg-[#080B11] rounded-xl border border-slate-800/60 text-xs">
                            <div class="flex justify-between font-semibold text-white">
                                <span>Assigned to {{ $assign->custodian->name }}</span>
                                <span class="px-2 py-0.5 rounded text-[9px] {{ $assign->status === 'active' ? 'bg-indigo-500/10 text-indigo-400' : 'bg-slate-800 text-slate-500' }}">
                                    {{ ucfirst($assign->status) }}
                                </span>
                            </div>
                            <div class="text-slate-400 mt-1">Office: {{ $assign->assigned_office }}</div>
                            <div class="text-[10px] text-slate-500 mt-1">Date: {{ $assign->assigned_date->format('d M, Y') }} @if($assign->actual_return_date) - Returned: {{ $assign->actual_return_date->format('d M, Y') }} @endif</div>
                        </div>
                    @empty
                        <div class="text-slate-600 italic text-xs">No allocations found.</div>
                    @endforelse
                </div>
            </div>

            <!-- Maintenance & Depreciation Logs -->
            <div class="space-y-4">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Maintenance & Valuation Log</h4>
                <div class="space-y-3">
                    @forelse($asset->maintenances as $maint)
                        <div class="p-3 bg-[#080B11] rounded-xl border border-slate-800/60 text-xs">
                            <div class="flex justify-between font-semibold text-white">
                                <span>{{ ucfirst($maint->maintenance_type) }}</span>
                                <span class="text-amber-400">৳{{ number_format($maint->cost, 2) }}</span>
                            </div>
                            <div class="text-slate-400 mt-1">{{ $maint->description }}</div>
                            <div class="text-[10px] text-slate-500 mt-1">Date: {{ $maint->maintenance_date->format('d M, Y') }} | Status: {{ ucfirst($maint->status) }}</div>
                        </div>
                    @empty
                        <div class="text-slate-600 italic text-xs text-left">No maintenance logs found.</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if($asset->asset_type !== 'consumer')
            const qrText = `{{ route('assets.show', $asset->id) }}
Asset ID: {{ $asset->unique_asset_id }}
Category: {{ $asset->category->name }}
Brand/Model: {{ $asset->brand }} {{ $asset->model }}
Serial: {{ $asset->serial_number }}`;

            new QRCode(document.getElementById("qrcode"), {
                text: qrText,
                width: 100,
                height: 100,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.L
            });
        @endif
    });

    function loadBranches(officeId, targetSelectId) {
        const branchSelect = document.getElementById(targetSelectId);
        branchSelect.innerHTML = '<option value="">Select Branch</option>';
        if (!officeId) return;
        fetch(`/api/offices/${officeId}/branches`)
            .then(res => res.json())
            .then(data => {
                data.forEach(branch => {
                    const option = document.createElement('option');
                    option.value = branch.id;
                    option.textContent = `${branch.name} (${branch.code})`;
                    branchSelect.appendChild(option);
                });
            });
    }

    // Asset Assign Cascading Loaders
    function loadBranchesForAssetAssign(officeId) {
        const branchSelect = document.getElementById('assign_branch_id');
        const deptSelect = document.getElementById('assign_department_id');
        const userSelect = document.getElementById('assign_custodian_id');

        branchSelect.innerHTML = '<option value="">Loading...</option>';
        deptSelect.innerHTML = '<option value="">Select Branch first</option>';
        userSelect.innerHTML = '<option value="">Select Custodian</option>';

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
            });
    }

    function loadDepartmentsForAssetAssign(branchId) {
        const deptSelect = document.getElementById('assign_department_id');
        const userSelect = document.getElementById('assign_custodian_id');

        deptSelect.innerHTML = '<option value="">Loading...</option>';
        userSelect.innerHTML = '<option value="">Select Custodian</option>';

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
            });
    }

    function loadUsersForAssetAssign() {
        const officeId = document.getElementById('assign_office_id').value;
        const branchId = document.getElementById('assign_branch_id').value;
        const deptId = document.getElementById('assign_department_id').value;
        const userSelect = document.getElementById('assign_custodian_id');

        userSelect.innerHTML = '<option value="">Loading...</option>';

        let url = `/api/users/filter?`;
        if (officeId) url += `office_id=${officeId}&`;
        if (branchId) url += `branch_id=${branchId}&`;
        if (deptId) url += `department_id=${deptId}&`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                userSelect.innerHTML = '<option value="">Select Custodian</option>';
                data.forEach(user => {
                    const opt = document.createElement('option');
                    opt.value = user.id;
                    opt.text = `${user.name} (${user.email})`;
                    userSelect.appendChild(opt);
                });
            });
    }

    // Asset Transfer Cascading Loaders
    function loadBranchesForTransfer(officeId) {
        const branchSelect = document.getElementById('transfer_branch_id');
        const deptSelect = document.getElementById('transfer_department_id');
        const userSelect = document.getElementById('transfer_custodian_id');

        branchSelect.innerHTML = '<option value="">Loading...</option>';
        deptSelect.innerHTML = '<option value="">Select Target Branch first</option>';
        userSelect.innerHTML = '<option value="">Select New Custodian</option>';

        if (!officeId) {
            branchSelect.innerHTML = '<option value="">Select Target Office first</option>';
            return;
        }

        fetch(`/api/offices/${officeId}/branches`)
            .then(res => res.json())
            .then(data => {
                branchSelect.innerHTML = '<option value="">Select Target Branch</option>';
                data.forEach(branch => {
                    const opt = document.createElement('option');
                    opt.value = branch.id;
                    opt.text = `${branch.name} (${branch.code})`;
                    branchSelect.appendChild(opt);
                });
            });
    }

    function loadDepartmentsForTransfer(branchId) {
        const deptSelect = document.getElementById('transfer_department_id');
        const userSelect = document.getElementById('transfer_custodian_id');

        deptSelect.innerHTML = '<option value="">Loading...</option>';
        userSelect.innerHTML = '<option value="">Select New Custodian</option>';

        if (!branchId) {
            deptSelect.innerHTML = '<option value="">Select Target Branch first</option>';
            return;
        }

        fetch(`/api/branches/${branchId}/departments`)
            .then(res => res.json())
            .then(data => {
                deptSelect.innerHTML = '<option value="">Select Target Department</option>';
                data.forEach(dept => {
                    const opt = document.createElement('option');
                    opt.value = dept.id;
                    opt.text = `${dept.name} (${dept.code})`;
                    deptSelect.appendChild(opt);
                });
            });
    }

    function loadUsersForTransfer() {
        const officeId = document.getElementById('transfer_office_id').value;
        const branchId = document.getElementById('transfer_branch_id').value;
        const deptId = document.getElementById('transfer_department_id').value;
        const userSelect = document.getElementById('transfer_custodian_id');

        userSelect.innerHTML = '<option value="">Loading...</option>';

        let url = `/api/users/filter?`;
        if (officeId) url += `office_id=${officeId}&`;
        if (branchId) url += `branch_id=${branchId}&`;
        if (deptId) url += `department_id=${deptId}&`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                userSelect.innerHTML = '<option value="">Select New Custodian</option>';
                data.forEach(user => {
                    const opt = document.createElement('option');
                    opt.value = user.id;
                    opt.text = `${user.name} (${user.email})`;
                    userSelect.appendChild(opt);
                });
            });
    }
</script>
@endsection
