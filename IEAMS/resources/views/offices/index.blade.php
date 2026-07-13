@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Office Registry</h2>
            <p class="text-xs text-slate-500 mt-1">Configure NHA structural offices and departments</p>
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
        
        <!-- Left: Offices List -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Registered Offices</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3.5">Office Code</th>
                            <th class="px-6 py-3.5">Office Name</th>
                            <th class="px-6 py-3.5 text-center">Branches</th>
                            <th class="px-6 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($offices as $office)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="px-6 py-4 font-mono font-bold text-indigo-600 text-xs">
                                    {{ $office->code }}
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-900">
                                    {{ $office->name }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-xs rounded-full font-medium">
                                        {{ $office->branches_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('offices.destroy', $office->id) }}" method="POST" onsubmit="return confirm('Are you sure? This will delete all associated branches!');" class="inline m-0">
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
                                    No offices registered. Create one using the form on the right.
                                </td>
                            </tr>
                        @endempty
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right: Create Office Form -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 h-fit space-y-4">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">Add New Office</h3>
            
            <form action="{{ route('offices.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-600 mb-1.5">Office Name</label>
                    <input type="text" id="name" name="name" required placeholder="e.g. Dhaka Head Office" class="w-full bg-white border border-slate-350 rounded-xl px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-[#0e76bc] focus:ring-1 focus:ring-[#0e76bc] transition">
                </div>

                <div>
                    <label for="code" class="block text-xs font-semibold text-slate-600 mb-1.5">Office Code (Unique)</label>
                    <input type="text" id="code" name="code" required placeholder="e.g. DHO" class="w-full bg-white border border-slate-350 rounded-xl px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-[#0e76bc] focus:ring-1 focus:ring-[#0e76bc] transition font-mono uppercase">
                </div>

                <button type="submit" class="w-full py-2.5 bg-[#0e76bc] hover:bg-[#0b5d94] text-white font-medium text-xs rounded-xl transition shadow-lg shadow-blue-500/10 cursor-pointer">
                    Register Office
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
