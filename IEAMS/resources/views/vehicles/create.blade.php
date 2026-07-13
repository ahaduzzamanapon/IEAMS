@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-4xl mx-auto">
    <div>
        <h2 class="text-3xl font-bold tracking-tight text-white">Register Vehicle</h2>
        <p class="text-sm text-slate-400 mt-1">Register government fleet vehicles, license, and certificate expiration values.</p>
    </div>

    <form action="{{ route('vehicles.store') }}" method="POST" class="space-y-8">
        @csrf

        <!-- 1. Vehicle Specifications -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-6">
            <h3 class="text-lg font-bold text-white border-b border-slate-850 pb-2">1. Vehicle Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Vehicle Number *</label>
                    <input type="text" name="vehicle_number" required placeholder="e.g. DHAKA METRO-KA-11-2222" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Vehicle Name *</label>
                    <input type="text" name="vehicle_name" required placeholder="e.g. Toyota Prado" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Vehicle Type *</label>
                    <select name="vehicle_type" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="sedan">Sedan Car</option>
                        <option value="jeep">SUV / Jeep</option>
                        <option value="microbus">Microbus</option>
                        <option value="truck">Truck</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Brand *</label>
                    <input type="text" name="brand" required placeholder="e.g. Toyota" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Model</label>
                    <input type="text" name="model" placeholder="e.g. Prado TXL" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Manufacturing Year</label>
                    <input type="number" name="manufacturing_year" min="1900" placeholder="2022" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Color *</label>
                    <input type="text" name="color" required placeholder="Black" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Chassis Number *</label>
                    <input type="text" name="chassis_number" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Engine Number *</label>
                    <input type="text" name="engine_number" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Vehicle Category *</label>
                    <input type="text" name="vehicle_category" required placeholder="Executive Car" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Fuel Type *</label>
                    <input type="text" name="fuel_type" required placeholder="e.g. Octane, Diesel" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Fuel Capacity (Liters) *</label>
                    <input type="number" step="0.01" name="fuel_quantity" required placeholder="60.00" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Seating Capacity</label>
                    <input type="number" name="seating_capacity" placeholder="7" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
            </div>
        </div>

        <!-- 2. Legal Registrations -->
        <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-6">
            <h3 class="text-lg font-bold text-white border-b border-slate-850 pb-2">2. Licensing & Certificate Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Registration Number *</label>
                    <input type="text" name="registration_number" required placeholder="REG-XXXXXX" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Registration Issue Date *</label>
                    <input type="date" name="registration_date" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Registration Expiry Date *</label>
                    <input type="date" name="registration_expiry_date" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Fitness Cert Number *</label>
                    <input type="text" name="fitness_certificate_number" required placeholder="FIT-XXXXXX" class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Fitness Issue Date *</label>
                    <input type="date" name="fitness_issue_date" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Fitness Expiry Date *</label>
                    <input type="date" name="fitness_expiry_date" required class="w-full bg-[#080B11] border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('vehicles.index') }}" class="px-6 py-2.5 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 font-medium text-sm rounded-xl transition">
                Cancel
            </a>
            <button type="submit" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-xl transition shadow-lg shadow-indigo-600/20">
                Register Vehicle
            </button>
        </div>
    </form>
</div>
@endsection
