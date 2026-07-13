@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-bold tracking-tight text-white">User & Role Management</h2>
        <p class="text-sm text-slate-400 mt-1">Configure system users, access roles, and assign security privileges.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Create Role Form -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-4 lg:col-span-1">
            <h3 class="text-lg font-bold text-white">Create Role</h3>
            <form action="{{ route('rbac.store-role') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Role Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Super Admin, Executive" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Role Slug *</label>
                    <input type="text" name="slug" required placeholder="e.g. super-admin, executive" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Assign Permissions</label>
                    <div class="space-y-2 max-h-40 overflow-y-auto p-3 bg-[#080B11] rounded-xl border border-slate-850">
                        @foreach($permissions as $perm)
                            <div class="flex items-center">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="perm_{{ $perm->id }}" class="w-4 h-4 text-indigo-600 bg-slate-900 border-slate-700 rounded focus:ring-indigo-500">
                                <label for="perm_{{ $perm->id }}" class="ml-2 text-xs text-slate-400">{{ $perm->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition">
                    Save Role
                </button>
            </form>
        </div>

        <!-- User Role Allocation -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 lg:col-span-2 space-y-6">
            <h3 class="text-lg font-bold text-white">Assign User Roles</h3>
            
            <form action="{{ route('rbac.assign-role') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end bg-[#080B11] p-4 rounded-xl border border-slate-850">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Select User</label>
                    <select name="user_id" required class="w-full bg-[#0E1325] border border-slate-700 rounded-xl px-3 py-2.5 text-xs text-white focus:outline-none">
                        <option value="">Choose User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Roles</label>
                    <select name="roles[]" multiple required class="w-full bg-[#0E1325] border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none h-10">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-xs rounded-xl transition">
                    Assign Roles
                </button>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-900/60 text-slate-400 text-xs uppercase font-medium">
                        <tr>
                            <th class="px-6 py-4">User Details</th>
                            <th class="px-6 py-4">Assigned Roles</th>
                            <th class="px-6 py-4">Granted Permissions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($users as $u)
                            <tr class="hover:bg-slate-800/20">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-white">{{ $u->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $u->email }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold">
                                    @if($u->roles->isNotEmpty())
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($u->roles as $role)
                                                <span class="px-2 py-0.5 bg-indigo-500/10 text-indigo-400 rounded-full text-[10px]">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-slate-650 italic">No assigned role</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-400">
                                    <div class="flex flex-wrap gap-1 max-w-sm">
                                        @foreach($u->roles as $role)
                                            @foreach($role->permissions as $p)
                                                <span class="px-1.5 py-0.5 bg-slate-850 rounded text-[9px] text-slate-400">
                                                    {{ $p->name }}
                                                </span>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-slate-500">No users found in database.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
