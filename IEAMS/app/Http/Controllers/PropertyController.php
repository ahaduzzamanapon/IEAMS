<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Land;
use App\Models\Plot;
use App\Models\Building;
use App\Models\Floor;
use App\Models\Apartment;
use App\Models\PropertySale;
use App\Models\Rent;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    // Projects CRUD
    public function projectsIndex(Request $request)
    {
        $projects = Project::latest()->paginate(10);
        $divisions = \App\Models\Division::orderBy('name')->get();
        return view('property.projects', compact('projects', 'divisions'));
    }

    public function storeProject(Request $request)
    {
        $validated = $request->validate([
            'project_id_code' => 'required|string|unique:projects,project_id_code',
            'project_code' => 'required|string|unique:projects,project_code',
            'name' => 'required|string|max:255',
            'division' => 'required|string',
            'district' => 'required|string',
            'upazila' => 'required|string',
            'mouza' => 'required|string',
            'total_land' => 'required|numeric|min:0',
            'total_road_land' => 'required|numeric|min:0',
            'estimated_project_cost' => 'nullable|numeric|min:0',
            'total_planned_plot' => 'required|integer|min:0',
            'total_planned_apartment' => 'required|integer|min:0',
            'project_start_date' => 'required|date',
            'expected_completion_date' => 'nullable|date|after_or_equal:project_start_date',
            'description' => 'nullable|string',
        ]);

        try {
            Project::create($validated);
            return back()->with('success', 'Project created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Update an existing housing project.
     */
    public function updateProject(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'project_id_code' => 'required|string|unique:projects,project_id_code,' . $id,
            'project_code' => 'required|string|unique:projects,project_code,' . $id,
            'name' => 'required|string|max:255',
            'division' => 'required|string',
            'district' => 'required|string',
            'upazila' => 'required|string',
            'mouza' => 'required|string',
            'total_land' => 'required|numeric|min:0',
            'total_road_land' => 'required|numeric|min:0',
            'estimated_project_cost' => 'nullable|numeric|min:0',
            'total_planned_plot' => 'required|integer|min:0',
            'total_planned_apartment' => 'required|integer|min:0',
            'project_start_date' => 'required|date',
            'expected_completion_date' => 'nullable|date|after_or_equal:project_start_date',
            'description' => 'nullable|string',
            'status' => 'required|in:planning,ongoing,completed,closed',
        ]);

        try {
            $project->update($validated);
            return back()->with('success', 'Project updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete an existing housing project.
     */
    public function destroyProject($id)
    {
        $project = Project::findOrFail($id);
        try {
            $project->delete();
            return back()->with('success', 'Project deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function showProject($id)
    {
        $project = Project::with(['lands', 'plots.buildings.floors.apartments'])->findOrFail($id);
        return view('property.project-show', compact('project'));
    }

    // Lands CRUD
    public function storeLand(Request $request, $projectId)
    {
        $validated = $request->validate([
            'purchase_date' => 'required|date',
            'purchase_value' => 'required|numeric|min:0',
            'seller_information' => 'required|string',
            'deed_number' => 'required|string',
            'registration_date' => 'required|date',
            'khatian_number' => 'required|string',
            'dag_number' => 'required|string',
            'land_amount' => 'required|numeric|min:0',
            'land_classification' => 'required|string',
        ]);

        $validated['project_id'] = $projectId;

        try {
            Land::create($validated);
            return back()->with('success', 'Land details added successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Plots CRUD
    public function storePlot(Request $request, $projectId)
    {
        $validated = $request->validate([
            'plot_number' => 'required|string',
            'plot_name' => 'nullable|string',
            'plot_area' => 'required|numeric|min:0',
        ]);

        $validated['project_id'] = $projectId;

        try {
            Plot::create($validated);
            return back()->with('success', 'Plot created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Buildings CRUD
    public function storeBuilding(Request $request, $plotId)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'number' => 'required|string',
            'footprint_area' => 'required|numeric|min:0',
            'total_floor' => 'required|integer|min:1',
            'has_lift' => 'nullable|boolean',
            'has_parking' => 'nullable|boolean',
        ]);

        $validated['plot_id'] = $plotId;
        $validated['has_lift'] = $request->has('has_lift');
        $validated['has_parking'] = $request->has('has_parking');

        try {
            Building::create($validated);
            return back()->with('success', 'Building added successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Floors CRUD
    public function storeFloor(Request $request, $buildingId)
    {
        $validated = $request->validate([
            'floor_number' => 'required|string',
            'floor_name' => 'nullable|string',
            'total_apartment' => 'required|integer|min:0',
        ]);

        $validated['building_id'] = $buildingId;

        try {
            Floor::create($validated);
            return back()->with('success', 'Floor added successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Apartments CRUD
    public function storeApartment(Request $request, $floorId)
    {
        $validated = $request->validate([
            'apartment_number' => 'required|string',
            'name' => 'required|string',
            'size' => 'required|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'balcony' => 'nullable|integer|min:0',
            'parking' => 'nullable|string',
            'utility_connection' => 'nullable|boolean',
            'orientation' => 'required|string',
        ]);

        $validated['floor_id'] = $floorId;
        $validated['utility_connection'] = $request->has('utility_connection');

        try {
            Apartment::create($validated);
            return back()->with('success', 'Apartment added successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Property Sale Management
    public function sales(Request $request)
    {
        $sales = PropertySale::with(['plot', 'apartment'])->latest()->paginate(10);
        $plots = Plot::where('status', 'vacant')->get();
        $apartments = Apartment::where('status', 'vacant')->get();
        return view('property.sales', compact('sales', 'plots', 'apartments'));
    }

    public function storeSale(Request $request)
    {
        $validated = $request->validate([
            'property_type' => 'required|in:plot,apartment',
            'plot_id' => 'nullable|required_if:property_type,plot|exists:plots,id',
            'apartment_id' => 'nullable|required_if:property_type,apartment|exists:apartments,id',
            'sale_date' => 'required|date',
            'buyer_name' => 'required|string',
            'father_name' => 'required|string',
            'mother_name' => 'required|string',
            'nid' => 'required|string',
            'passport' => 'nullable|string',
            'mobile' => 'required|string',
            'email' => 'nullable|email',
            'address' => 'required|string',
            'sale_value' => 'required|numeric|min:1',
            'registration_number' => 'nullable|string',
            'registration_date' => 'nullable|date',
            'deed_number' => 'nullable|string',
            'payment_status' => 'required|in:pending,partially_paid,paid',
            'handover_date' => 'nullable|date',
        ]);

        try {
            PropertySale::create($validated);
            return back()->with('success', 'Property sold successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    // Rent Management
    public function rents(Request $request)
    {
        $rents = Rent::with('apartment')->latest()->paginate(10);
        $apartments = Apartment::where('status', 'vacant')->get();
        return view('property.rents', compact('rents', 'apartments'));
    }

    public function storeRent(Request $request)
    {
        $validated = $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'tenant_name' => 'required|string',
            'nid' => 'required|string',
            'mobile' => 'required|string',
            'address' => 'required|string',
            'occupation' => 'required|string',
            'rent_start_date' => 'required|date',
            'rent_end_date' => 'required|date|after:rent_start_date',
            'monthly_rent' => 'required|numeric|min:1',
            'advance_amount' => 'required|numeric|min:0',
            'security_deposit' => 'required|numeric|min:0',
            'agreement_number' => 'nullable|string',
        ]);

        try {
            Rent::create($validated);
            return back()->with('success', 'Apartment rented successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Update an existing rent agreement.
     */
    public function updateRent(Request $request, $id)
    {
        $rent = Rent::findOrFail($id);

        $validated = $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'tenant_name' => 'required|string',
            'nid' => 'required|string',
            'mobile' => 'required|string',
            'address' => 'required|string',
            'occupation' => 'required|string',
            'rent_start_date' => 'required|date',
            'rent_end_date' => 'required|date|after:rent_start_date',
            'monthly_rent' => 'required|numeric|min:1',
            'advance_amount' => 'required|numeric|min:0',
            'security_deposit' => 'required|numeric|min:0',
            'agreement_number' => 'nullable|string',
        ]);

        try {
            $rent->update($validated);
            return back()->with('success', 'Rent Agreement updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete an existing rent agreement.
     */
    public function destroyRent($id)
    {
        $rent = Rent::findOrFail($id);
        try {
            $rent->delete();
            return back()->with('success', 'Rent Agreement deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Property Reports
    public function reports(Request $request)
    {
        $projects = Project::withCount(['plots', 'plots as occupied_plots_count' => function ($q) {
            $q->where('status', '!=', 'vacant');
        }])->get();

        $salesQuery = PropertySale::with(['plot', 'apartment']);
        if ($request->filled('sale_start')) {
            $salesQuery->whereDate('sale_date', '>=', $request->sale_start);
        }
        if ($request->filled('sale_end')) {
            $salesQuery->whereDate('sale_date', '<=', $request->sale_end);
        }
        $sales = $salesQuery->get();

        $rentsQuery = Rent::with('apartment');
        if ($request->filled('rent_start')) {
            $rentsQuery->whereDate('rent_start_date', '>=', $request->rent_start);
        }
        if ($request->filled('rent_end')) {
            $rentsQuery->whereDate('rent_end_date', '<=', $request->rent_end);
        }
        $rents = $rentsQuery->get();

        return view('property.reports', compact('projects', 'sales', 'rents'));
    }
}
