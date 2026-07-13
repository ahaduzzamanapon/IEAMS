@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">
    <div>
        <h2 class="text-3xl font-bold tracking-tight text-white">Register Asset</h2>
        <p class="text-sm text-slate-400 mt-1">Register Fixed Assets, Current Assets, or Consumer items into the NHA registry with full compliance checks.</p>
    </div>

    <!-- Error Messages -->
    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('assets.store') }}" method="POST" id="assetForm" class="space-y-8">
        @csrf

        <!-- 1. Basic Information -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-6">
            <h3 class="text-lg font-bold text-white border-b border-slate-850 pb-2">1. Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Asset ID *</label>
                    <input type="text" value="AUTO-GENERATED" disabled class="w-full bg-[#05070C] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-slate-400 font-mono cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Asset Type *</label>
                    <select name="asset_type" id="asset_type" onchange="toggleFields()" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="fixed" {{ old('asset_type') == 'fixed' ? 'selected' : '' }}>Fixed Asset</option>
                        <option value="current" {{ old('asset_type') == 'current' ? 'selected' : '' }}>Current Asset</option>
                        <option value="consumer" {{ old('asset_type') == 'consumer' ? 'selected' : '' }}>Consumer asset</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Category *</label>
                    <select name="category_id" id="category_id" onchange="filterSubCategories()" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" data-type="{{ $cat->asset_type }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Sub-Category / Product Name *</label>
                    <select name="sub_category_id" id="sub_category_id" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                        @foreach($subCategories as $sub)
                            <option value="{{ $sub->id }}" data-category="{{ $sub->category_id }}" {{ old('sub_category_id') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Brand, Model, Serial, Quantity -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Brand *</label>
                    <input type="text" name="brand" id="brand" value="{{ old('brand') }}" placeholder="e.g. Dell, HP" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 disabled:opacity-50 disabled:bg-[#05070C] disabled:cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Model *</label>
                    <input type="text" name="model" id="model" value="{{ old('model') }}" placeholder="e.g. Latitude 5430" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 disabled:opacity-50 disabled:bg-[#05070C] disabled:cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Serial Number *</label>
                    <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number') }}" placeholder="e.g. SN-XXXXXXX" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 disabled:opacity-50 disabled:bg-[#05070C] disabled:cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Quantity *</label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" min="1" placeholder="e.g. 50" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 disabled:opacity-50 disabled:bg-[#05070C] disabled:cursor-not-allowed">
                </div>
            </div>
        </div>

        <!-- 2. Procurement Information -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-6">
            <h3 class="text-lg font-bold text-white border-b border-slate-850 pb-2">2. Procurement Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Purchase Date *</label>
                    <input type="date" name="purchase_date" id="purchase_date" value="{{ old('purchase_date') }}" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 disabled:opacity-50 disabled:bg-[#05070C] disabled:cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Purchase Cost (৳) *</label>
                    <input type="number" step="0.01" name="purchase_cost" id="purchase_cost" value="{{ old('purchase_cost') }}" oninput="calculateProcurementAndDepr()" placeholder="0.00" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 disabled:opacity-50 disabled:bg-[#05070C] disabled:cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Capitalized Cost (৳) *</label>
                    <input type="number" step="0.01" name="capitalized_cost" id="capitalized_cost" value="{{ old('capitalized_cost') }}" oninput="calculateProcurementAndDepr()" placeholder="0.00" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 disabled:opacity-50 disabled:bg-[#05070C] disabled:cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Total Cost (৳) *</label>
                    <input type="text" name="total_cost" id="total_cost" value="{{ old('total_cost') }}" disabled placeholder="0.00" class="w-full bg-[#05070C] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-slate-400 font-semibold cursor-not-allowed">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Purchase Order Number *</label>
                    <input type="text" name="purchase_order_number" id="purchase_order_number" value="{{ old('purchase_order_number') }}" placeholder="PO-XXXX" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 disabled:opacity-50 disabled:bg-[#05070C] disabled:cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Invoice Number *</label>
                    <input type="text" name="invoice_number" id="invoice_number" value="{{ old('invoice_number') }}" placeholder="INV-XXXX" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 disabled:opacity-50 disabled:bg-[#05070C] disabled:cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Vendor Name *</label>
                    <select name="vendor_id" id="vendor_id" onchange="updateVendorDetails()" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 disabled:opacity-50 disabled:bg-[#05070C] disabled:cursor-not-allowed">
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Auto Populated Vendor details -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 p-5 rounded-2xl bg-slate-500/5 border border-slate-500/10">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Vendor Address (Auto)</label>
                    <textarea id="vendor_address" rows="1" disabled placeholder="No Address" class="w-full bg-slate-500/5 border border-slate-500/10 rounded-xl px-4 py-2 text-xs text-slate-400 cursor-not-allowed resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Contact Person (Auto)</label>
                    <input type="text" id="vendor_contact_person" disabled placeholder="No Contact Person" class="w-full bg-slate-500/5 border border-slate-500/10 rounded-xl px-4 py-2 text-xs text-slate-400 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Mobile (Auto)</label>
                    <input type="text" id="vendor_mobile" disabled placeholder="No Mobile" class="w-full bg-slate-500/5 border border-slate-500/10 rounded-xl px-4 py-2 text-xs text-slate-400 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Email (Auto)</label>
                    <input type="text" id="vendor_email" disabled placeholder="No Email" class="w-full bg-slate-500/5 border border-slate-500/10 rounded-xl px-4 py-2 text-xs text-slate-400 cursor-not-allowed">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- 3. Warranty Information -->
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-6">
                <h3 class="text-lg font-bold text-white border-b border-slate-850 pb-2">3. Warranty Information</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-2">Warranty Applicable *</label>
                        <div class="flex items-center gap-6">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="warranty_applicable" id="warranty_yes" value="1" onchange="toggleWarrantyDates()" class="w-4 h-4 text-indigo-600 bg-slate-900 border-slate-700 focus:ring-indigo-500 disabled:opacity-50">
                                <span class="ml-2 text-sm text-slate-300">Yes</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="warranty_applicable" id="warranty_no" value="0" checked onchange="toggleWarrantyDates()" class="w-4 h-4 text-indigo-600 bg-slate-900 border-slate-700 focus:ring-indigo-500 disabled:opacity-50">
                                <span class="ml-2 text-sm text-slate-300">No</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5">Warranty Start Date</label>
                            <input type="date" name="warranty_start_date" id="warranty_start_date" value="{{ old('warranty_start_date') }}" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-indigo-500 disabled:opacity-50 disabled:bg-[#05070C] disabled:cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5">Warranty End Date</label>
                            <input type="date" name="warranty_end_date" id="warranty_end_date" value="{{ old('warranty_end_date') }}" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-indigo-500 disabled:opacity-50 disabled:bg-[#05070C] disabled:cursor-not-allowed">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Depreciation Information -->
            <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-6">
                <h3 class="text-lg font-bold text-white border-b border-slate-850 pb-2">4. Depreciation Information</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Depreciation Method *</label>
                        <select name="depreciation_method" id="depreciation_method" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-indigo-500 disabled:opacity-50 disabled:bg-[#05070C] disabled:cursor-not-allowed">
                            <option value="straight-line">Straight-Line Method</option>
                            <option value="written-down-value">Written Down Value</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5">Useful Life (Years) *</label>
                            <input type="number" name="useful_life" id="useful_life" value="{{ old('useful_life') }}" min="1" placeholder="e.g. 5" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-indigo-500 disabled:opacity-50 disabled:bg-[#05070C] disabled:cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5">Salvage Value (%) *</label>
                            <input type="number" step="0.01" name="salvage_value_percentage" id="salvage_value_percentage" value="{{ old('salvage_value_percentage') }}" oninput="calculateProcurementAndDepr()" placeholder="e.g. 10" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-indigo-500 disabled:opacity-50 disabled:bg-[#05070C] disabled:cursor-not-allowed">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5">Salvage Value Amount (৳)</label>
                            <input type="text" name="salvage_value_amount" id="salvage_value_amount" disabled placeholder="0.00" class="w-full bg-[#05070C] border border-slate-700/80 rounded-xl px-4 py-2 text-sm text-slate-400 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5">Current Book Value (৳)</label>
                            <input type="text" name="current_book_value" id="current_book_value" disabled placeholder="0.00" class="w-full bg-[#05070C] border border-slate-700/80 rounded-xl px-4 py-2 text-sm text-slate-400 cursor-not-allowed">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('assets.index') }}" class="px-6 py-2.5 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 font-semibold text-sm rounded-xl transition">
                Cancel
            </a>
            <button type="submit" class="px-8 py-2.5 bg-[#0e76bc] hover:bg-[#0b5d94] text-white font-semibold text-sm rounded-xl transition shadow-lg shadow-indigo-600/20">
                Register Asset
            </button>
        </div>
    </form>
</div>

<script>
    const allCategories = @json($categories);
    const allSubCategories = @json($subCategories);
    const vendors = @json($vendors);

    function filterCategories() {
        const selectedType = document.getElementById('asset_type').value;
        const categorySelect = document.getElementById('category_id');
        const currentSelectedCatId = categorySelect.value;
        
        categorySelect.innerHTML = '';
        
        const filteredCats = allCategories.filter(cat => cat.asset_type === selectedType);
        
        filteredCats.forEach(cat => {
            const opt = document.createElement('option');
            opt.value = cat.id;
            opt.textContent = cat.name;
            if (cat.id == currentSelectedCatId) {
                opt.selected = true;
            }
            categorySelect.appendChild(opt);
        });
        
        filterSubCategories();
    }

    function filterSubCategories() {
        const categorySelect = document.getElementById('category_id');
        const selectedCategoryId = categorySelect.value;
        const subSelect = document.getElementById('sub_category_id');
        const currentSelectedSubId = subSelect.value;
        
        subSelect.innerHTML = '';
        
        const filteredSubs = allSubCategories.filter(sub => sub.category_id == selectedCategoryId);
        
        filteredSubs.forEach(sub => {
            const opt = document.createElement('option');
            opt.value = sub.id;
            opt.textContent = sub.name;
            if (sub.id == currentSelectedSubId) {
                opt.selected = true;
            }
            subSelect.appendChild(opt);
        });
    }

    function updateVendorDetails() {
        const vendorId = document.getElementById('vendor_id').value;
        const addressInput = document.getElementById('vendor_address');
        const contactInput = document.getElementById('vendor_contact_person');
        const mobileInput = document.getElementById('vendor_mobile');
        const emailInput = document.getElementById('vendor_email');
        
        if (!vendorId) {
            addressInput.value = '';
            contactInput.value = '';
            mobileInput.value = '';
            emailInput.value = '';
            return;
        }
        
        const vendor = vendors.find(v => v.id == vendorId);
        if (vendor) {
            addressInput.value = vendor.address || '';
            contactInput.value = vendor.contact_person || '';
            mobileInput.value = vendor.mobile || '';
            emailInput.value = vendor.email || '';
        }
    }

    function calculateProcurementAndDepr() {
        const purchaseCost = parseFloat(document.getElementById('purchase_cost').value || 0);
        const capitalizedCost = parseFloat(document.getElementById('capitalized_cost').value || 0);
        const totalCost = purchaseCost + capitalizedCost;
        
        document.getElementById('total_cost').value = totalCost.toFixed(2);
        
        // Salvage value calculation
        const salvagePct = parseFloat(document.getElementById('salvage_value_percentage').value || 0);
        const salvageAmt = totalCost * (salvagePct / 100);
        document.getElementById('salvage_value_amount').value = salvageAmt.toFixed(2);
        
        // Current Book Value initially equals Total Cost
        document.getElementById('current_book_value').value = totalCost.toFixed(2);
    }

    function toggleFields() {
        const type = document.getElementById('asset_type').value;
        const isConsumer = (type === 'consumer');
        
        // Brand, Model, Serial
        const brand = document.getElementById('brand');
        const model = document.getElementById('model');
        const serial = document.getElementById('serial_number');
        
        brand.disabled = isConsumer;
        brand.required = !isConsumer;
        if (isConsumer) brand.value = '';
        
        model.disabled = isConsumer;
        model.required = !isConsumer;
        if (isConsumer) model.value = '';
        
        serial.disabled = isConsumer;
        serial.required = !isConsumer;
        if (isConsumer) serial.value = '';
        
        // Quantity
        const quantity = document.getElementById('quantity');
        quantity.disabled = !isConsumer;
        quantity.required = isConsumer;
        if (!isConsumer) quantity.value = '';
        
        // Procurement fields
        const procurementInputs = [
            'purchase_date', 'purchase_cost', 'capitalized_cost',
            'purchase_order_number', 'invoice_number', 'vendor_id'
        ];
        procurementInputs.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.disabled = isConsumer;
                el.required = !isConsumer;
                if (isConsumer) el.value = '';
            }
        });
        
        if (isConsumer) {
            document.getElementById('vendor_address').value = '';
            document.getElementById('vendor_contact_person').value = '';
            document.getElementById('vendor_mobile').value = '';
            document.getElementById('vendor_email').value = '';
            document.getElementById('total_cost').value = '';
        }
        
        // Warranty radio buttons
        const wYes = document.getElementById('warranty_yes');
        const wNo = document.getElementById('warranty_no');
        wYes.disabled = isConsumer;
        wNo.disabled = isConsumer;
        if (isConsumer) {
            wNo.checked = true;
        }
        
        toggleWarrantyDates();
        
        // Depreciation inputs
        const deprInputs = ['depreciation_method', 'useful_life', 'salvage_value_percentage'];
        deprInputs.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.disabled = isConsumer;
                el.required = !isConsumer;
                if (isConsumer) el.value = '';
            }
        });
        
        if (isConsumer) {
            document.getElementById('salvage_value_amount').value = '';
            document.getElementById('current_book_value').value = '';
        } else {
            calculateProcurementAndDepr();
        }
        
        filterCategories();
    }

    function toggleWarrantyDates() {
        const isConsumer = document.getElementById('asset_type').value === 'consumer';
        const isApplicable = document.getElementById('warranty_yes').checked && !isConsumer;
        
        const wStart = document.getElementById('warranty_start_date');
        const wEnd = document.getElementById('warranty_end_date');
        
        wStart.disabled = !isApplicable;
        wStart.required = isApplicable;
        if (!isApplicable) wStart.value = '';
        
        wEnd.disabled = !isApplicable;
        wEnd.required = isApplicable;
        if (!isApplicable) wEnd.value = '';
    }

    // Client-side validations
    document.getElementById('assetForm').addEventListener('submit', function(e) {
        const isConsumer = document.getElementById('asset_type').value === 'consumer';
        const isApplicable = document.getElementById('warranty_yes').checked && !isConsumer;
        
        if (isApplicable) {
            const wStart = new Date(document.getElementById('warranty_start_date').value);
            const wEnd = new Date(document.getElementById('warranty_end_date').value);
            
            if (wEnd <= wStart) {
                e.preventDefault();
                alert('Warranty End Date must be after the Warranty Start Date.');
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Initial filter & toggle on load
        const initialType = document.getElementById('asset_type').value;
        const initialCat = document.getElementById('category_id').value;
        
        toggleFields();
        updateVendorDetails();
    });
</script>
@endsection
