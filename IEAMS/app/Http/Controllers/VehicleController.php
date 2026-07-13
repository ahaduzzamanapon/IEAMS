<?php
namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\VehicleAssignment;
use App\Models\VehicleMaintenance;
use App\Models\User;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::query();

        if ($request->filled('type')) {
            $query->where('vehicle_type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('vehicle_number', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('vehicle_name', 'like', "%{$search}%");
            });
        }

        $vehicles = $query->latest()->paginate(10);
        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_number' => 'required|string|unique:vehicles,vehicle_number',
            'registration_number' => 'required|string|unique:vehicles,registration_number',
            'registration_date' => 'required|date',
            'registration_expiry_date' => 'required|date|after:registration_date',
            'fitness_certificate_number' => 'required|string',
            'fitness_issue_date' => 'required|date',
            'fitness_expiry_date' => 'required|date|after:fitness_issue_date',
            'tax_token_number' => 'nullable|string',
            'tax_token_issue_date' => 'nullable|date',
            'tax_token_expiry_date' => 'nullable|date|after_or_equal:tax_token_issue_date',
            'insurance_number' => 'nullable|string',
            'insurance_company' => 'nullable|string',
            'insurance_issue_date' => 'nullable|date',
            'insurance_expiry_date' => 'nullable|date|after_or_equal:insurance_issue_date',
            'vehicle_type' => 'required|string',
            'vehicle_category' => 'required|string',
            'vehicle_name' => 'required|string',
            'brand' => 'required|string',
            'model' => 'nullable|string',
            'manufacturing_year' => 'nullable|integer|min:1900',
            'color' => 'required|string',
            'fuel_type' => 'required|string',
            'fuel_quantity' => 'required|numeric|min:0',
            'chassis_number' => 'required|string|unique:vehicles,chassis_number',
            'engine_number' => 'required|string|unique:vehicles,engine_number',
            'seating_capacity' => 'nullable|integer|min:1',
        ]);

        try {
            Vehicle::create($validated);
            return redirect()->route('vehicles.index')->with('success', 'Vehicle registered successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $vehicle = Vehicle::with('assignments.officer', 'assignments.driver')->findOrFail($id);
        $offices = \App\Models\Office::all();
        $drivers = Driver::where('status', 'active')->get();
        return view('vehicles.show', compact('vehicle', 'offices', 'drivers'));
    }

    // Drivers Management
    public function drivers()
    {
        $drivers = Driver::latest()->paginate(10);
        return view('vehicles.drivers', compact('drivers'));
    }

    public function storeDriver(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mobile' => 'required|string',
            'nid' => 'required|string',
            'driving_license_number' => 'required|string|unique:drivers,driving_license_number',
            'license_issue_date' => 'required|date',
            'license_expiry_date' => 'nullable|date|after:license_issue_date',
            'license_category' => 'nullable|string',
            'blood_group' => 'nullable|string',
            'permanent_address' => 'required|string',
            'present_address' => 'required|string',
            'emergency_contact' => 'required|string',
        ]);

        try {
            Driver::create($validated);
            return redirect()->route('vehicles.drivers')->with('success', 'Driver registered successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    // Vehicle Assignment
    public function assign(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $validated = $request->validate([
            'assigned_office' => 'nullable|string',
            'assigned_officer_id' => 'required|exists:users,id',
            'assigned_driver_id' => 'required|exists:drivers,id',
            'assignment_date' => 'required|date',
            'purpose' => 'required|string',
            'expected_return_date' => 'nullable|date|after_or_equal:assignment_date',
        ]);

        if (empty($validated['assigned_office'])) {
            $officer = User::with('office')->find($validated['assigned_officer_id']);
            $validated['assigned_office'] = $officer && $officer->office ? $officer->office->name : 'NHA HQ';
        }

        try {
            $vehicle->assignments()->create($validated);
            return back()->with('success', 'Vehicle assigned successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function return(Request $request, $id)
    {
        $assignment = VehicleAssignment::where('vehicle_id', $id)
            ->where('status', 'active')
            ->firstOrFail();

        $request->validate([
            'actual_return_date' => 'required|date|after_or_equal:assignment_date',
        ]);

        try {
            $assignment->update([
                'status' => 'returned',
                'actual_return_date' => $request->actual_return_date
            ]);

            $assignment->vehicle->update(['status' => 'available']);

            return back()->with('success', 'Vehicle returned successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Reports
    public function reports(Request $request)
    {
        $vehicles = Vehicle::with('assignments.driver')->get();
        $drivers = Driver::all();
        $assignments = VehicleAssignment::with(['vehicle', 'driver', 'officer'])->latest()->get();

        return view('vehicles.reports', compact('vehicles', 'drivers', 'assignments'));
    }

    /**
     * Store a new Vehicle Maintenance record (SRS 4.2.6)
     * BR-08: Sets vehicle status to 'under_maintenance' on creation
     */
    public function storeMaintenance(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $validated = $request->validate([
            'maintenance_type' => 'required|in:repair,servicing,inspection',
            'maintenance_date' => 'required|date',
            'workshop_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
        ]);
        try {
            $validated['vehicle_id'] = $vehicle->id;
            $validated['status'] = 'ongoing';
            VehicleMaintenance::create($validated);
            return back()->with('success', 'Maintenance record logged. Vehicle status set to Under Maintenance.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mark Vehicle Maintenance as completed (SRS BR-09)
     * Restores vehicle status to previous/available
     */
    public function completeMaintenance(Request $request, $vehicleId, $maintenanceId)
    {
        $maintenance = VehicleMaintenance::where('vehicle_id', $vehicleId)->findOrFail($maintenanceId);
        try {
            $validated = $request->validate([
                'completion_date' => 'required|date|after_or_equal:' . $maintenance->maintenance_date->toDateString(),
            ]);
            $maintenance->update([
                'status' => 'completed',
                'completion_date' => $validated['completion_date'],
            ]);
            return back()->with('success', 'Maintenance completed. Vehicle status restored.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
