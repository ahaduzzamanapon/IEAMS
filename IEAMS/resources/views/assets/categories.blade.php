@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-bold tracking-tight text-white">Category Management</h2>
        <p class="text-sm text-slate-400 mt-1">Define Asset Types, Categories, and Sub-Categories/Products.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Create Category Form -->
        <div class="p-6 rounded-2xl bg-[#0E132F]/80 border border-slate-800/80 space-y-6">
            <h3 class="text-lg font-bold text-white">Create Asset Category</h3>
            <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Asset Type</label>
                    <select name="asset_type" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="fixed">Fixed Asset</option>
                        <option value="current">Current Asset</option>
                        <option value="consumer">Consumer asset</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Category Name</label>
                    <input type="text" name="name" required placeholder="e.g. Computer Equipment, Office Supplies" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Category Code</label>
                    <input type="text" name="code" required placeholder="e.g. COMP, OFF" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition">
                    Save Category
                </button>
            </form>
        </div>

        <!-- Create Sub-Category Form -->
        <div class="p-6 rounded-2xl bg-[#0E132F]/80 border border-slate-800/80 space-y-6">
            <h3 class="text-lg font-bold text-white">Create Sub-Category / Product Name</h3>
            <form action="{{ route('subcategories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Parent Category</label>
                    <select name="category_id" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }} ({{ strtoupper($category->asset_type) }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Sub-Category Name</label>
                    <input type="text" name="name" required placeholder="e.g. Laptop, Desks, Printers" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Sub-Category Code</label>
                    <input type="text" name="code" required placeholder="e.g. LAP, DSK" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition">
                    Save Sub-Category
                </button>
            </form>
        </div>

    </div>

    <!-- Category Registry List -->
    <div class="p-6 rounded-2xl bg-[#0E132F]/80 border border-slate-800/80">
        <h3 class="text-lg font-bold text-white mb-6">Registered Categories</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 text-xs uppercase font-medium">
                    <tr>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Category Code & Name</th>
                        <th class="px-6 py-4">Sub-Categories / Products</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($categories as $category)
                        <tr class="hover:bg-slate-800/20">
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $category->asset_type === 'fixed' ? 'bg-indigo-500/10 text-indigo-400' : ($category->asset_type === 'current' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-amber-500/10 text-amber-400') }}">
                                    {{ strtoupper($category->asset_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-white">[{{ $category->code }}] {{ $category->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-400">
                                @if($category->subCategories->isNotEmpty())
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($category->subCategories as $sub)
                                            <span class="px-2 py-0.5 bg-slate-800 text-xs rounded border border-slate-700/60 text-slate-300">
                                                [{{ $sub->code }}] {{ $sub->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-slate-600 italic">No sub-categories defined</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-slate-500">No categories found. Create a category above to get started.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
