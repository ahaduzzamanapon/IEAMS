<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LandController;
use App\Http\Controllers\PlotController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected system routes
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // User & Role Management (RBAC)
    Route::get('/rbac', [RolePermissionController::class, 'index'])->name('rbac.index');
    Route::post('/rbac/store-role', [RolePermissionController::class, 'storeRole'])->name('rbac.store-role');
    Route::post('/rbac/assign-role', [RolePermissionController::class, 'assignRole'])->name('rbac.assign-role');

    // User CRUD Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/api/users/filter', [UserController::class, 'filterUsers'])->name('api.users.filter');

    // Asset Management
    Route::get('/assets/dashboard', [DashboardController::class, 'assetDashboard'])->name('assets.dashboard');
    Route::get('/assets', [AssetController::class, 'index'])->name('assets.index');
    Route::get('/assets/create', [AssetController::class, 'create'])->name('assets.create');
    Route::post('/assets/store', [AssetController::class, 'store'])->name('assets.store');
    Route::get('/assets/{id}', [AssetController::class, 'show'])->name('assets.show');
    Route::post('/assets/{id}/assign', [AssetController::class, 'assign'])->name('assets.assign');
    Route::post('/assets/{id}/return', [AssetController::class, 'return'])->name('assets.return');
    Route::post('/assets/{id}/transfer', [AssetController::class, 'transfer'])->name('assets.transfer');
    Route::post('/assets/{id}/maintenance', [AssetController::class, 'maintenance'])->name('assets.maintenance');
    Route::post('/assets/{id}/maintenance/{maintenanceId}/complete', [AssetController::class, 'completeMaintenance'])->name('assets.complete-maintenance');

    // GET fallbacks for POST actions to redirect to the show page cleanly
    Route::get('/assets/{id}/assign', function ($id) { return redirect()->route('assets.show', $id); });
    Route::get('/assets/{id}/return', function ($id) { return redirect()->route('assets.show', $id); });
    Route::get('/assets/{id}/transfer', function ($id) { return redirect()->route('assets.show', $id); });
    Route::get('/assets/{id}/maintenance', function ($id) { return redirect()->route('assets.show', $id); });


    Route::get('/vendors', [AssetController::class, 'vendors'])->name('vendors.index');
    Route::post('/vendors/store', [AssetController::class, 'storeVendor'])->name('vendors.store');

    Route::get('/categories', [AssetController::class, 'categories'])->name('categories.index');
    Route::post('/categories/store', [AssetController::class, 'storeCategory'])->name('categories.store');
    Route::post('/subcategories/store', [AssetController::class, 'storeSubCategory'])->name('subcategories.store');

    // Office CRUD Setup
    Route::get('/offices', [OfficeController::class, 'index'])->name('offices.index');
    Route::post('/offices', [OfficeController::class, 'store'])->name('offices.store');
    Route::delete('/offices/{id}', [OfficeController::class, 'destroy'])->name('offices.destroy');

    // Branch CRUD Setup
    Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
    Route::post('/branches', [BranchController::class, 'store'])->name('branches.store');
    Route::delete('/branches/{id}', [BranchController::class, 'destroy'])->name('branches.destroy');
    Route::get('/api/offices/{officeId}/branches', [BranchController::class, 'getBranchesByOffice'])->name('api.offices.branches');

    // Department CRUD Setup
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
    Route::get('/api/branches/{branchId}/departments', [DepartmentController::class, 'getDepartmentsByBranch'])->name('api.branches.departments');

    // Designation CRUD Setup
    Route::get('/designations', [DesignationController::class, 'index'])->name('designations.index');
    Route::post('/designations', [DesignationController::class, 'store'])->name('designations.store');
    Route::delete('/designations/{id}', [DesignationController::class, 'destroy'])->name('designations.destroy');
    Route::get('/api/departments/{departmentId}/designations', [DesignationController::class, 'getDesignationsByDepartment'])->name('api.departments.designations');

    Route::get('/assets-reports', [AssetController::class, 'reports'])->name('assets.reports');

    // Bangladesh Geocode Setup CRUDs & API
    Route::get('/setups/divisions', [\App\Http\Controllers\DivisionController::class, 'index'])->name('divisions.index');
    Route::post('/setups/divisions/store', [\App\Http\Controllers\DivisionController::class, 'store'])->name('divisions.store');
    Route::delete('/setups/divisions/{id}', [\App\Http\Controllers\DivisionController::class, 'destroy'])->name('divisions.destroy');

    Route::get('/setups/districts', [\App\Http\Controllers\DistrictController::class, 'index'])->name('districts.index');
    Route::post('/setups/districts/store', [\App\Http\Controllers\DistrictController::class, 'store'])->name('districts.store');
    Route::delete('/setups/districts/{id}', [\App\Http\Controllers\DistrictController::class, 'destroy'])->name('districts.destroy');

    Route::get('/setups/upazilas', [\App\Http\Controllers\UpazilaController::class, 'index'])->name('upazilas.index');
    Route::post('/setups/upazilas/store', [\App\Http\Controllers\UpazilaController::class, 'store'])->name('upazilas.store');
    Route::delete('/setups/upazilas/{id}', [\App\Http\Controllers\UpazilaController::class, 'destroy'])->name('upazilas.destroy');

    Route::get('/api/geocode/divisions/{divisionId}/districts', [\App\Http\Controllers\DistrictController::class, 'getDistrictsByDivision'])->name('api.geocode.districts');
    Route::get('/api/geocode/districts/{districtId}/upazilas', [\App\Http\Controllers\UpazilaController::class, 'getUpazilasByDistrict'])->name('api.geocode.upazilas');

    // Notifications CRUD & API
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::get('/api/notifications/unread', [\App\Http\Controllers\NotificationController::class, 'unread'])->name('api.notifications.unread');
    Route::post('/api/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('api.notifications.read');

    // Property & Project Management
    Route::get('/property/dashboard', [DashboardController::class, 'propertyDashboard'])->name('property.dashboard');
    Route::get('/property/projects', [PropertyController::class, 'projectsIndex'])->name('property.projects');
    Route::post('/property/projects/store', [PropertyController::class, 'storeProject'])->name('property.store-project');
    Route::get('/property/projects/{id}', [PropertyController::class, 'showProject'])->name('property.project-show');
    Route::put('/property/projects/{id}', [PropertyController::class, 'updateProject'])->name('property.update-project');
    Route::delete('/property/projects/{id}', [PropertyController::class, 'destroyProject'])->name('property.destroy-project');
    Route::post('/property/projects/{projectId}/land', [PropertyController::class, 'storeLand'])->name('property.store-land');
    Route::post('/property/projects/{projectId}/plot', [PropertyController::class, 'storePlot'])->name('property.store-plot');
    Route::post('/property/plots/{plotId}/building', [PropertyController::class, 'storeBuilding'])->name('property.store-building');
    Route::post('/property/buildings/{buildingId}/floor', [PropertyController::class, 'storeFloor'])->name('property.store-floor');
    Route::post('/property/floors/{floorId}/apartment', [PropertyController::class, 'storeApartment'])->name('property.store-apartment');

    // Individual Property Module CRUD Resource Routes
    Route::resource('property/lands', LandController::class)->names('property.lands');
    Route::resource('property/plots', PlotController::class)->names('property.plots');
    Route::resource('property/buildings', BuildingController::class)->names('property.buildings');
    Route::resource('property/floors', FloorController::class)->names('property.floors');
    Route::resource('property/apartments', ApartmentController::class)->names('property.apartments');

    Route::get('/property/sales', [PropertyController::class, 'sales'])->name('property.sales');
    Route::post('/property/sales/store', [PropertyController::class, 'storeSale'])->name('property.store-sale');
    Route::get('/property/rents', [PropertyController::class, 'rents'])->name('property.rents');
    Route::post('/property/rents/store', [PropertyController::class, 'storeRent'])->name('property.store-rent');
    Route::put('/property/rents/{id}', [PropertyController::class, 'updateRent'])->name('property.update-rent');
    Route::delete('/property/rents/{id}', [PropertyController::class, 'destroyRent'])->name('property.destroy-rent');

    Route::get('/property-reports', [PropertyController::class, 'reports'])->name('property.reports');

    Route::get('/vehicles/drivers', [VehicleController::class, 'drivers'])->name('vehicles.drivers');
    Route::post('/vehicles/drivers/store', [VehicleController::class, 'storeDriver'])->name('vehicles.store-driver');

    Route::get('/vehicles/dashboard', [DashboardController::class, 'vehicleDashboard'])->name('vehicles.dashboard');
    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicles/create', [VehicleController::class, 'create'])->name('vehicles.create');
    Route::post('/vehicles/store', [VehicleController::class, 'store'])->name('vehicles.store');
    Route::get('/vehicles/{id}', [VehicleController::class, 'show'])->name('vehicles.show');
    Route::post('/vehicles/{id}/assign', [VehicleController::class, 'assign'])->name('vehicles.assign');
    Route::post('/vehicles/{id}/return', [VehicleController::class, 'return'])->name('vehicles.return');
    Route::post('/vehicles/{id}/maintenance', [VehicleController::class, 'storeMaintenance'])->name('vehicles.maintenance.store');
    Route::post('/vehicles/{vehicleId}/maintenance/{maintenanceId}/complete', [VehicleController::class, 'completeMaintenance'])->name('vehicles.maintenance.complete');

    Route::get('/vehicles-reports', [VehicleController::class, 'reports'])->name('vehicles.reports');

    // GET fallbacks for Vehicle POST actions to redirect to the show page cleanly
    Route::get('/vehicles/{id}/assign', function ($id) { return redirect()->route('vehicles.show', $id); });
    Route::get('/vehicles/{id}/return', function ($id) { return redirect()->route('vehicles.show', $id); });
    Route::get('/vehicles/{id}/maintenance', function ($id) { return redirect()->route('vehicles.show', $id); });

    // GET fallbacks for Property transaction stores to redirect cleanly
    Route::get('/property/sales/store', function () { return redirect()->route('property.sales'); });
    Route::get('/property/rents/store', function () { return redirect()->route('property.rents'); });

    // GET fallbacks for Project child registration forms
    Route::get('/property/projects/{projectId}/land', function ($projectId) { return redirect()->route('property.project-show', $projectId); });
    Route::get('/property/projects/{projectId}/plot', function ($projectId) { return redirect()->route('property.project-show', $projectId); });
    Route::get('/property/plots/{plotId}/building', function () { return redirect()->route('property.projects'); });
    Route::get('/property/buildings/{buildingId}/floor', function () { return redirect()->route('property.projects'); });
    Route::get('/property/floors/{floorId}/apartment', function () { return redirect()->route('property.projects'); });

    // API Dependency Cascading Dropdowns
    Route::get('/api/projects/{projectId}/lands', function ($projectId) {
        return response()->json(\App\Models\Land::where('project_id', $projectId)->get(['id', 'deed_number', 'khatian_number', 'dag_number']));
    });
    Route::get('/api/projects/{projectId}/plots', function ($projectId) {
        return response()->json(\App\Models\Plot::where('project_id', $projectId)->get(['id', 'plot_number', 'plot_name']));
    });
    Route::get('/api/lands/{landId}/plots', function ($landId) {
        return response()->json(\App\Models\Plot::where('land_id', $landId)->get(['id', 'plot_number', 'plot_name']));
    });
    Route::get('/api/plots/{plotId}/buildings', function ($plotId) {
        return response()->json(\App\Models\Building::where('plot_id', $plotId)->get(['id', 'name', 'number']));
    });
    Route::get('/api/buildings/{buildingId}/floors', function ($buildingId) {
        return response()->json(\App\Models\Floor::where('building_id', $buildingId)->get(['id', 'floor_number', 'floor_name']));
    });
    Route::get('/api/floors/{floorId}/apartments', function ($floorId) {
        return response()->json(\App\Models\Apartment::where('floor_id', $floorId)->get(['id', 'apartment_number', 'name']));
    });

    // System Optimization & Cache Clear
    Route::post('/clear-cache', function () {
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('optimize');
        return back()->with('success', 'Application optimized and all caches cleared successfully!');
    })->name('system.clear-cache');
    Route::get('/clear-cache', function () {
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('optimize');
        return back()->with('success', 'Application optimized and all caches cleared successfully!');
    })->name('system.clear-cache');

    Route::get('/run-migrations', function () {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            return '<h1>Migration Success</h1><pre>' . \Illuminate\Support\Facades\Artisan::output() . '</pre><p><a href="/">Go back to Dashboard</a></p>';
        } catch (\Exception $e) {
            return '<h1>Migration Failed</h1><pre>' . $e->getMessage() . '</pre>';
        }
    });

});
