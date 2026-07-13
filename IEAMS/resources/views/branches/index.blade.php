@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Branch Registry</h2>
            <p class="text-xs text-slate-500 mt-1">Configure sub-branches linked to specific structural offices</p>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs rounded-xl flex items-center gap-2">
            <span>✓</span> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-100 text-rose-600 text-xs rounded-xl space-y-1">
            @foreach($errors->all() as $err)
                <div>• {{ $err }}</div>
            @endforeach
        </div>
    @endif

    <!-- Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Branches List -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Registered Branches</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3.5">Branch Code</th>
                            <th class="px-6 py-3.5">Branch Name</th>
                            <th class="px-6 py-3.5">Linked Office</th>
                            <th class="px-6 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($branches as $branch)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="px-6 py-4 font-mono font-bold text-indigo-600 text-xs">
                                    {{ $branch->code }}
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-900">
                                    {{ $branch->name }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-xs rounded-full font-bold">
                                        {{ $branch->office->name }} ({{ $branch->office->code }})
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this branch?');" class="inline m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold text-xs rounded-lg transition cursor-pointer">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">
                                    No branches registered. Create one using the form on the right.
                                </td>
                            </tr>
                        @endempty
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right: Create Branch Form -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 h-fit space-y-4">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">Add New Branch</h3>
            
            <form action="{{ route('branches.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="office_id" class="block text-xs font-semibold text-slate-600 mb-1.5">Linked Parent Office</label>
                    <select id="office_id" name="office_id" required class="w-full bg-white border border-slate-350 rounded-xl px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-[#0e76bc] focus:ring-1 focus:ring-[#0e76bc] transition">
                        <option value="">Select Parent Office</option>
                        @foreach($offices as $office)
                            <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>
                                {{ $office->name }} ({{ $office->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-600 mb-1.5">Branch Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g. Mirpur sub-branch" class="w-full bg-white border border-slate-350 rounded-xl px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-[#0e76bc] focus:ring-1 focus:ring-[#0e76bc] transition">
                </div>

                <div>
                    <label for="code" class="block text-xs font-semibold text-slate-600 mb-1.5">Branch Code</label>
                    <input type="text" id="code" name="code" value="{{ old('code') }}" required placeholder="e.g. MIR" class="w-full bg-white border border-slate-350 rounded-xl px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-[#0e76bc] focus:ring-1 focus:ring-[#0e76bc] transition font-mono uppercase">
                </div>

                <button type="submit" class="w-full py-2.5 bg-[#0e76bc] hover:bg-[#0b5d94] text-white font-medium text-xs rounded-xl transition shadow-lg shadow-blue-500/10 cursor-pointer">
                    Register Branch
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
