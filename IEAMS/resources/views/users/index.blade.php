@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">User Accounts</h2>
            <p class="text-sm text-slate-400 mt-1">Manage system administrators, officers, and assigned system access privileges.</p>
        </div>
        <div>
            <a href="{{ route('users.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition shadow-lg shadow-indigo-600/20">
                + Register New User
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="p-4 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('users.index') }}" method="GET" class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-800 hover:bg-slate-700 text-white font-medium text-xs rounded-xl transition cursor-pointer">
                    Search
                </button>
                <a href="{{ route('users.index') }}" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800/60 text-slate-400 hover:text-slate-200 font-medium text-xs rounded-xl transition flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- User Table List -->
    <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 text-xs uppercase font-medium">
                    <tr>
                        <th class="px-6 py-4">User Details</th>
                        <th class="px-6 py-4">Assigned Roles</th>
                        <th class="px-6 py-4">Created Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-800/20 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full border border-slate-800/80 overflow-hidden bg-slate-900 flex items-center justify-center shrink-0">
                                        @if($user->photo_path)
                                            <img src="{{ asset('storage/' . $user->photo_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-sm font-bold text-slate-500 font-mono">{{ substr($user->name, 0, 2) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-semibold text-white flex items-center gap-2">
                                            {{ $user->name }}
                                            @if($user->employee_id)
                                                <span class="px-1.5 py-0.5 bg-indigo-500/10 text-indigo-400 font-mono text-[9px] rounded font-bold uppercase">{{ $user->employee_id }}</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-slate-400 mt-0.5">{{ $user->email }} @if($user->phone) | {{ $user->phone }} @endif</div>
                                        @if($user->designation || $user->department || $user->branch || $user->office)
                                            <div class="text-[9px] text-slate-500 mt-1 flex flex-wrap gap-1">
                                                @if($user->designation)
                                                    <span class="px-1.5 py-0.5 bg-slate-800 rounded" title="Designation">{{ $user->designation->name }}</span>
                                                @endif
                                                @if($user->department)
                                                    <span class="px-1.5 py-0.5 bg-slate-800/80 text-slate-400 rounded" title="Department">{{ $user->department->name }}</span>
                                                @endif
                                                @if($user->branch)
                                                    <span class="px-1.5 py-0.5 bg-slate-900 rounded text-slate-500" title="Branch">{{ $user->branch->name }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->roles->isNotEmpty())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($user->roles as $role)
                                            <span class="px-2.5 py-0.5 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-full text-[10px] font-semibold">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-slate-500 italic text-xs">No role assigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">
                                <div>
                                    <span class="px-2 py-0.5 text-[9px] font-bold rounded-full {{ $user->status === 'active' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }}">
                                        {{ strtoupper($user->status ?? 'ACTIVE') }}
                                    </span>
                                </div>
                                <div class="mt-1.5 text-[10px]">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('users.edit', $user->id) }}" class="px-3 py-1.5 bg-indigo-600/10 hover:bg-indigo-600 text-indigo-400 hover:text-white font-medium text-xs rounded-lg transition">
                                        Edit
                                    </a>
                                    
                                    @if($user->id !== Auth::id())
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline m-0" onsubmit="return confirm('Are you sure you want to delete user \'{{ $user->name }}\'? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-rose-600/10 hover:bg-rose-600 text-rose-400 hover:text-white font-medium text-xs rounded-lg transition cursor-pointer">
                                                Delete
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-3 py-1.5 bg-slate-800/40 text-slate-600 font-medium text-xs rounded-lg select-none cursor-not-allowed" title="You cannot delete yourself">
                                            Delete
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500">No users found matching criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
