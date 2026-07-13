@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">Division Setup</h2>
            <p class="text-sm text-slate-400 mt-1">Manage Bangladesh administrative divisions and web portal records.</p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Create Form -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-6">
            <h3 class="text-lg font-bold text-white">Register Division</h3>
            <form action="{{ route('divisions.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Division ID (Numeric Code) *</label>
                    <input type="number" name="id" required value="{{ old('id') }}" placeholder="e.g. 1" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">English Name *</label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g. Dhaka" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Bangla Name</label>
                    <input type="text" name="bn_name" value="{{ old('bn_name') }}" placeholder="e.g. ঢাকা" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Portal URL</label>
                    <input type="text" name="url" value="{{ old('url') }}" placeholder="e.g. www.dhakadiv.gov.bd" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition cursor-pointer">
                    Save Division
                </button>
            </form>
        </div>

        <!-- List Table -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-white">Registered Divisions</h3>
                <form action="{{ route('divisions.index') }}" method="GET" class="w-48">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name..." class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-1.5 text-xs text-white focus:outline-none focus:border-indigo-500">
                </form>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-900/60 text-slate-400 text-xs uppercase font-medium">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">English Name</th>
                            <th class="px-6 py-4">Bangla Name</th>
                            <th class="px-6 py-4">Portal URL</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($divisions as $div)
                            <tr class="hover:bg-slate-800/20">
                                <td class="px-6 py-4 font-mono">{{ $div->id }}</td>
                                <td class="px-6 py-4 font-semibold text-white">{{ $div->name }}</td>
                                <td class="px-6 py-4 text-slate-400">{{ $div->bn_name ?? '-' }}</td>
                                <td class="px-6 py-4 text-xs font-mono text-indigo-400">{{ $div->url ?? '-' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" onclick="showEditModal({{ json_encode($div) }})" class="px-2.5 py-1.5 bg-amber-600/10 hover:bg-amber-600 text-amber-400 hover:text-white font-medium text-xs rounded-lg transition cursor-pointer">
                                            Edit
                                        </button>
                                        <form action="{{ route('divisions.destroy', $div->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this division? This will delete all associated districts and upazilas.');" class="inline m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1.5 bg-rose-600/10 hover:bg-rose-600 text-rose-400 hover:text-white font-medium text-xs rounded-lg transition cursor-pointer">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">No divisions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $divisions->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden px-4">
    <div class="bg-[#0E132F] border border-slate-800 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-2">
            <h3 class="text-md font-bold text-white uppercase tracking-wider">Edit Division</h3>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-200 cursor-pointer">✕</button>
        </div>
        <form id="editForm" action="{{ route('divisions.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="id" id="edit_id">
            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-1.5">English Name *</label>
                <input type="text" name="name" id="edit_name" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-1.5">Bangla Name</label>
                <input type="text" name="bn_name" id="edit_bn_name" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-1.5">Portal URL</label>
                <input type="text" name="url" id="edit_url" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-705 text-slate-350 font-semibold text-xs rounded-xl transition cursor-pointer">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs rounded-xl transition cursor-pointer">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showEditModal(div) {
        document.getElementById('edit_id').value = div.id;
        document.getElementById('edit_name').value = div.name;
        document.getElementById('edit_bn_name').value = div.bn_name || '';
        document.getElementById('edit_url').value = div.url || '';
        document.getElementById('editModal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endsection
