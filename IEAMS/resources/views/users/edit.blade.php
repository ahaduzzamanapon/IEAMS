@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">Edit User Account</h2>
            <p class="text-sm text-slate-400 mt-1">Modify account details and update role-based security assignments for {{ $user->name }}.</p>
        </div>
        <a href="{{ route('users.index') }}" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 hover:text-white font-medium text-sm rounded-xl transition">
            ← Back to Users
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
        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="space-y-1.5">
                    <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Full Name *</label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $user->name) }}" placeholder="e.g. Abdullah Al Mamun" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <!-- Email -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Email Address *</label>
                    <input type="email" name="email" id="email" required value="{{ old('email', $user->email) }}" placeholder="e.g. mamun@nha.gov.bd" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>
            </div>

            <div class="p-4 bg-slate-900/30 border border-slate-800 rounded-xl space-y-4">
                <h4 class="text-sm font-semibold text-slate-300">Change Password (Optional)</h4>
                <p class="text-xs text-slate-500">Leave these fields empty if you do not want to change the current password.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                    <!-- Password -->
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">New Password</label>
                        <input type="password" name="password" id="password" placeholder="Minimum 8 characters" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Re-type password" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                    </div>
                </div>
            </div>

            <!-- Employee Personal Information -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="space-y-1.5">
                    <label for="employee_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Employee ID / Code</label>
                    <input type="text" name="employee_id" id="employee_id" value="{{ old('employee_id', $user->employee_id) }}" placeholder="e.g. EMP1002" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 transition font-mono uppercase">
                </div>

                <div class="space-y-1.5">
                    <label for="phone" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Phone / Mobile</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="e.g. 01712345678" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 transition">
                </div>

                <div class="space-y-1.5">
                    <label for="nid" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">NID Number</label>
                    <input type="text" name="nid" id="nid" value="{{ old('nid', $user->nid) }}" placeholder="e.g. 1995123456789" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 transition">
                </div>

                <div class="space-y-1.5">
                    <label for="blood_group" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Blood Group</label>
                    <select name="blood_group" id="blood_group" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 transition">
                        <option value="">Select Group</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                            <option value="{{ $bg }}" {{ old('blood_group', $user->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Photo & Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label for="photo" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Profile Photo</label>
                    <input type="file" name="photo" id="photo" accept="image/*" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition">
                    @if($user->photo_path)
                        <div class="text-[11px] text-slate-400 mt-1.5 flex items-center gap-2">
                            <img src="{{ asset('storage/' . $user->photo_path) }}" alt="photo" class="w-8 h-8 rounded-full border border-slate-700 object-cover">
                            <span>Current: <a href="{{ asset('storage/' . $user->photo_path) }}" target="_blank" class="text-indigo-400 hover:underline">View Photo</a></span>
                        </div>
                    @endif
                    <p class="text-[10px] text-slate-500 mt-1">Allowed formats: JPG, JPEG, PNG (Max 2MB). Leave blank to keep current.</p>
                </div>

                <div class="space-y-1.5">
                    <label for="status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Account Status *</label>
                    <select name="status" id="status" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 transition">
                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Office, Branch, Department, and Designation cascading dependencies -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="space-y-1.5">
                    <label for="office_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Office</label>
                    <select name="office_id" id="office_id" onchange="loadBranchesForOffice(this.value)" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition">
                        <option value="">Select Office</option>
                        @foreach($offices as $office)
                            <option value="{{ $office->id }}" {{ old('office_id', $user->office_id) == $office->id ? 'selected' : '' }}>
                                {{ $office->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="branch_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Branch</label>
                    <select name="branch_id" id="branch_id" onchange="loadDepartmentsForBranch(this.value)" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition">
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $user->branch_id) == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="department_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Department</label>
                    <select name="department_id" id="department_id" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition">
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="designation_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Designation</label>
                    <select name="designation_id" id="designation_id" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-3 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition">
                        <option value="">Select Designation</option>
                        @foreach($designations as $desg)
                            <option value="{{ $desg->id }}" {{ old('designation_id', $user->designation_id) == $desg->id ? 'selected' : '' }}>
                                {{ $desg->name }} ({{ $desg->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Addresses -->
            <div class="space-y-4 p-4 bg-slate-900/30 border border-slate-800/80 rounded-2xl">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Address Details</h4>
                    <label class="inline-flex items-center text-[10px] text-slate-400 cursor-pointer">
                        <input type="checkbox" id="same_address_check" onclick="copyPresentAddress()" class="w-3.5 h-3.5 text-indigo-600 bg-slate-900 border-slate-700 rounded focus:ring-indigo-500 mr-2">
                        Permanent same as Present
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label for="present_address" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Present Address</label>
                        <textarea name="present_address" id="present_address" rows="3" placeholder="Enter present residential address" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 transition">{{ old('present_address', $user->present_address) }}</textarea>
                    </div>

                    <div class="space-y-1.5">
                        <label for="permanent_address" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Permanent Address</label>
                        <textarea name="permanent_address" id="permanent_address" rows="3" placeholder="Enter permanent home address" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 transition">{{ old('permanent_address', $user->permanent_address) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Role Assignment -->
            <div class="space-y-3">
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Assign Security Roles</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($roles as $role)
                        @php
                            $isChecked = is_array(old('roles')) 
                                ? in_array($role->id, old('roles')) 
                                : $user->roles->contains($role->id);
                        @endphp
                        <div class="p-4 rounded-xl bg-[#080B11] border border-slate-800/80 hover:border-indigo-500/50 transition cursor-pointer relative flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}" class="w-4 h-4 text-indigo-600 bg-slate-900 border-slate-700 rounded focus:ring-indigo-500" {{ $isChecked ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="role_{{ $role->id }}" class="font-medium text-white cursor-pointer select-none">
                                    {{ $role->name }}
                                </label>
                                <p class="text-slate-500 text-[10px] mt-0.5 font-mono">slug: {{ $role->slug }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4 pt-4 border-t border-slate-800/80">
                <a href="{{ route('users.index') }}" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-slate-400 hover:text-white font-medium text-sm rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl transition shadow-lg shadow-indigo-600/20 cursor-pointer">
                    Update Account
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function copyPresentAddress() {
        const check = document.getElementById('same_address_check');
        const present = document.getElementById('present_address');
        const permanent = document.getElementById('permanent_address');
        if (check.checked) {
            permanent.value = present.value;
        }
    }

    function loadBranchesForOffice(officeId, selectedBranchId = null) {
        const branchSelect = document.getElementById('branch_id');
        const deptSelect = document.getElementById('department_id');
        branchSelect.innerHTML = '<option value="">Loading...</option>';
        deptSelect.innerHTML = '<option value="">Select Branch first</option>';
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
                    if (selectedBranchId && branch.id == selectedBranchId) {
                        opt.selected = true;
                    }
                    branchSelect.appendChild(opt);
                });
                if (selectedBranchId) {
                    loadDepartmentsForBranch(selectedBranchId, "{{ old('department_id', $user->department_id) }}");
                }
            })
            .catch(() => {
                branchSelect.innerHTML = '<option value="">Failed to load branches</option>';
            });
    }

    function loadDepartmentsForBranch(branchId, selectedDeptId = null) {
        const deptSelect = document.getElementById('department_id');
        deptSelect.innerHTML = '<option value="">Loading...</option>';
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
                    if (selectedDeptId && dept.id == selectedDeptId) {
                        opt.selected = true;
                    }
                    deptSelect.appendChild(opt);
                });
            })
            .catch(() => {
                deptSelect.innerHTML = '<option value="">Failed to load departments</option>';
            });
    }
</script>
@endsection
