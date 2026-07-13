<?php
namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Vendor;
use App\Models\User;
use App\Models\AssetAssignment;
use App\Models\AssetTransfer;
use App\Models\AssetMaintenance;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::with(['category', 'subCategory']);

        if ($request->filled('type')) {
            $query->where('asset_type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('maintenance_status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('unique_asset_id', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        $assets = $query->latest()->paginate(10);
        return view('assets.index', compact('assets'));
    }

    public function create()
    {
        $categories = Category::all();
        $subCategories = SubCategory::all();
        $vendors = Vendor::all();
        return view('assets.create', compact('categories', 'subCategories', 'vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_type' => 'required|in:fixed,current,consumer',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'brand' => 'nullable|string',
            'model' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'quantity' => 'nullable|integer|min:1',
            'purchase_date' => 'nullable|date',
            'purchase_cost' => 'nullable|numeric|min:0',
            'capitalized_cost' => 'nullable|numeric|min:0',
            'purchase_order_number' => 'nullable|string',
            'invoice_number' => 'nullable|string',
            'vendor_id' => 'nullable|exists:vendors,id',
            'warranty_applicable' => 'nullable|boolean',
            'warranty_start_date' => 'nullable|date',
            'warranty_end_date' => 'nullable|date',
            'depreciation_method' => 'nullable|string',
            'useful_life' => 'nullable|integer|min:1',
            'salvage_value_percentage' => 'nullable|numeric|between:0,100',
        ]);

        $validated['warranty_applicable'] = $request->boolean('warranty_applicable');

        try {
            Asset::create($validated);
            return redirect()->route('assets.index')->with('success', 'Asset registered successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $asset = Asset::with([
            'category', 'subCategory', 'vendor',
            'assignments.custodian', 'transfers.fromCustodian', 'transfers.toCustodian',
            'maintenances', 'depreciationLogs'
        ])->findOrFail($id);

        $users = User::all();
        $offices = \App\Models\Office::all();
        return view('assets.show', compact('asset', 'users', 'offices'));
    }

    // Assign Asset
    public function assign(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);

        $validated = $request->validate([
            'custodian_id' => 'required|exists:users,id',
            'office_id' => 'required|exists:offices,id',
            'branch_id' => 'nullable|exists:branches,id',
            'assigned_date' => 'required|date',
            'expected_return_date' => 'nullable|date|after_or_equal:assigned_date',
        ]);

        try {
            $asset->assignments()->create($validated);
            return back()->with('success', 'Asset assigned successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Return Assigned Asset
    public function return(Request $request, $id)
    {
        $assignment = AssetAssignment::where('asset_id', $id)
            ->where('status', 'active')
            ->firstOrFail();

        $minDate = $assignment->assigned_date instanceof \Carbon\Carbon
            ? $assignment->assigned_date->format('Y-m-d')
            : (string) $assignment->assigned_date;

        $request->validate([
            'actual_return_date' => 'required|date|after_or_equal:' . $minDate,
        ]);

        try {
            $assignment->update([
                'status' => 'returned',
                'actual_return_date' => $request->actual_return_date
            ]);

            // Revert asset maintenance_status to available
            $assignment->asset->update(['maintenance_status' => 'available']);

            return back()->with('success', 'Asset returned successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Transfer Asset
    public function transfer(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);

        $validated = $request->validate([
            'to_custodian_id' => 'required|exists:users,id',
            'to_office_id' => 'required|exists:offices,id',
            'to_branch_id' => 'nullable|exists:branches,id',
            'transfer_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        // Find last active assignment/custodian
        $lastAssignment = $asset->assignments()->where('status', 'active')->first();
        $validated['from_custodian_id'] = $lastAssignment ? $lastAssignment->custodian_id : null;
        $validated['from_office'] = $lastAssignment ? $lastAssignment->assigned_office : null;

        try {
            $asset->transfers()->create($validated);
            return back()->with('success', 'Asset transferred successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Maintenance logs
    public function maintenance(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);

        $validated = $request->validate([
            'maintenance_type' => 'required|in:repair,servicing',
            'maintenance_date' => 'required|date',
            'cost' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        try {
            $asset->maintenances()->create($validated);
            return back()->with('success', 'Asset maintenance logged and status set to under maintenance.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function completeMaintenance(Request $request, $id, $maintenanceId)
    {
        $maintenance = AssetMaintenance::findOrFail($maintenanceId);

        try {
            $maintenance->update(['status' => 'completed']);
            return back()->with('success', 'Maintenance marked as completed and asset status restored.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Vendors CRUD
    public function vendors()
    {
        $vendors = Vendor::latest()->paginate(10);
        return view('assets.vendors', compact('vendors'));
    }

    public function storeVendor(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'mobile' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        Vendor::create($validated);
        return back()->with('success', 'Vendor registered successfully.');
    }

    // Categories CRUD
    public function categories()
    {
        $categories = Category::with('subCategories')->get();
        return view('assets.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'asset_type' => 'required|in:fixed,current,consumer',
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:categories,code|max:10',
        ]);

        Category::create($validated);
        return back()->with('success', 'Category created successfully.');
    }

    public function storeSubCategory(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10',
        ]);

        SubCategory::create($validated);
        return back()->with('success', 'Sub-category created successfully.');
    }

    // Asset Reports
    public function reports(Request $request)
    {
        $query = Asset::with(['category', 'subCategory', 'vendor', 'assignments.custodian']);

        if ($request->filled('type')) {
            $query->where('asset_type', $request->type);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            $query->where('maintenance_status', $request->status);
        }

        $assets = $query->latest()->get();
        $categories = Category::all();

        return view('assets.reports', compact('assets', 'categories'));
    }
}
