<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Project;
use App\Models\Plot;
use App\Models\Building;
use App\Models\Apartment;
use App\Models\PropertySale;
use App\Models\Rent;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\DepreciationLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function assetDashboard()
    {
        $totalAssets = Asset::count();
        $activeAssets = Asset::where('maintenance_status', 'available')->orWhere('maintenance_status', 'assigned')->count();
        $assignedAssets = Asset::where('maintenance_status', 'assigned')->count();
        $availableAssets = Asset::where('maintenance_status', 'available')->count();
        $underWarranty = Asset::where('warranty_applicable', true)->where('warranty_end_date', '>=', now())->count();
        $warrantyExpired = Asset::where('warranty_applicable', true)->where('warranty_end_date', '<', now())->count();
        $underMaintenance = Asset::where('maintenance_status', 'under_maintenance')->count();
        $scrapAssets = Asset::where('maintenance_status', 'scrap')->count();
        $disposedAssets = Asset::where('maintenance_status', 'disposed')->count();
        $monthlyDepreciation = DepreciationLog::whereMonth('depreciation_date', Carbon::now()->month)->sum('depreciation_amount');
        $currentAssetValue = Asset::sum('current_book_value');

        return view('assets.dashboard', compact(
            'totalAssets', 'activeAssets', 'assignedAssets', 'availableAssets',
            'underWarranty', 'warrantyExpired', 'underMaintenance', 'scrapAssets',
            'disposedAssets', 'monthlyDepreciation', 'currentAssetValue'
        ));
    }

    public function index()
    {
        // 1. Asset Metrics
        $totalAssets = Asset::count();
        $activeAssets = Asset::where('maintenance_status', 'available')->orWhere('maintenance_status', 'assigned')->count();
        $assignedAssets = Asset::where('maintenance_status', 'assigned')->count();
        $availableAssets = Asset::where('maintenance_status', 'available')->count();
        $underWarranty = Asset::where('warranty_applicable', true)->where('warranty_end_date', '>=', now())->count();
        $warrantyExpired = Asset::where('warranty_applicable', true)->where('warranty_end_date', '<', now())->count();
        $underMaintenance = Asset::where('maintenance_status', 'under_maintenance')->count();
        $scrapAssets = Asset::where('maintenance_status', 'scrap')->count();
        $disposedAssets = Asset::where('maintenance_status', 'disposed')->count();
        $monthlyDepreciation = DepreciationLog::whereMonth('depreciation_date', Carbon::now()->month)->sum('depreciation_amount');
        $currentAssetValue = Asset::sum('current_book_value');

        // 2. Property Metrics
        $totalProjects = Project::count();
        $activeProjects = Project::where('status', 'ongoing')->count();
        $completedProjects = Project::where('status', 'completed')->count();
        $totalLandArea = Project::sum('total_land');
        $totalPlots = Plot::count();
        $vacantPlots = Plot::where('status', 'vacant')->count();
        $soldPlots = Plot::where('status', 'sold')->count();
        $underConstructionPlots = Plot::whereHas('buildings', function ($q) {
            $q->where('construction_status', 'under_construction');
        })->count();
        $totalBuildings = Building::count();
        $underConstructionBuildings = Building::where('construction_status', 'under_construction')->count();
        $completedBuildings = Building::where('construction_status', 'completed')->count();

        // Apartment details
        $totalApartments = Apartment::count();
        $vacantApartments = Apartment::where('status', 'vacant')->count();
        $soldApartments = Apartment::where('status', 'sold')->count();
        $rentedApartments = Apartment::where('status', 'rented')->count();

        $totalPlotSales = PropertySale::where('property_type', 'plot')->sum('sale_value');
        $totalApartmentSales = PropertySale::where('property_type', 'apartment')->sum('sale_value');
        $totalRentalIncome = Rent::sum('monthly_rent');

        // 3. Vehicle Metrics
        $totalVehicles = Vehicle::count();
        $availableVehicles = Vehicle::where('status', 'available')->count();
        $assignedVehicles = Vehicle::where('status', 'assigned')->count();
        $underMaintenanceVehicles = Vehicle::where('status', 'under_maintenance')->count();
        $accidentVehicles = Vehicle::where('status', 'accident')->count();
        $outOfServiceVehicles = Vehicle::where('status', 'out_of_service')->count();
        $totalDrivers = Driver::count();
        $activeDrivers = Driver::where('status', 'active')->count();

        return view('dashboard', compact(
            'totalAssets', 'activeAssets', 'assignedAssets', 'availableAssets',
            'underWarranty', 'warrantyExpired', 'underMaintenance', 'scrapAssets',
            'disposedAssets', 'monthlyDepreciation', 'currentAssetValue',
            'totalProjects', 'activeProjects', 'completedProjects', 'totalLandArea',
            'totalPlots', 'vacantPlots', 'soldPlots', 'underConstructionPlots',
            'totalBuildings', 'underConstructionBuildings', 'completedBuildings',
            'totalApartments', 'vacantApartments', 'soldApartments', 'rentedApartments',
            'totalPlotSales', 'totalApartmentSales', 'totalRentalIncome',
            'totalVehicles', 'availableVehicles', 'assignedVehicles', 'underMaintenanceVehicles',
            'accidentVehicles', 'outOfServiceVehicles', 'totalDrivers', 'activeDrivers'
        ));
    }

    public function propertyDashboard()
    {
        $totalProjects = Project::count();
        $activeProjects = Project::where('status', 'ongoing')->count();
        $completedProjects = Project::where('status', 'completed')->count();
        $totalLandArea = Project::sum('total_land');
        $totalPlots = Plot::count();
        $vacantPlots = Plot::where('status', 'vacant')->count();
        $soldPlots = Plot::where('status', 'sold')->count();
        $underConstructionPlots = Plot::whereHas('buildings', function ($q) {
            $q->where('construction_status', 'under_construction');
        })->count();
        $totalBuildings = Building::count();
        $underConstructionBuildings = Building::where('construction_status', 'under_construction')->count();
        $completedBuildings = Building::where('construction_status', 'completed')->count();
        $totalApartments = Apartment::count();
        $vacantApartments = Apartment::where('status', 'vacant')->count();
        $soldApartments = Apartment::where('status', 'sold')->count();
        $rentedApartments = Apartment::where('status', 'rented')->count();
        $totalPlotSales = PropertySale::where('property_type', 'plot')->sum('sale_value');
        $totalApartmentSales = PropertySale::where('property_type', 'apartment')->sum('sale_value');
        $totalRentalIncome = Rent::sum('monthly_rent');

        return view('property.dashboard', compact(
            'totalProjects', 'activeProjects', 'completedProjects', 'totalLandArea',
            'totalPlots', 'vacantPlots', 'soldPlots', 'underConstructionPlots',
            'totalBuildings', 'underConstructionBuildings', 'completedBuildings',
            'totalApartments', 'vacantApartments', 'soldApartments', 'rentedApartments',
            'totalPlotSales', 'totalApartmentSales', 'totalRentalIncome'
        ));
    }

    public function vehicleDashboard()
    {
        $totalVehicles = Vehicle::count();
        $availableVehicles = Vehicle::where('status', 'available')->count();
        $assignedVehicles = Vehicle::where('status', 'assigned')->count();
        $underMaintenanceVehicles = Vehicle::where('status', 'under_maintenance')->count();
        $accidentVehicles = Vehicle::where('status', 'accident')->count();
        $outOfServiceVehicles = Vehicle::where('status', 'out_of_service')->count();
        $totalDrivers = Driver::count();
        $activeDrivers = Driver::where('status', 'active')->count();

        return view('vehicles.dashboard', compact(
            'totalVehicles', 'availableVehicles', 'assignedVehicles', 'underMaintenanceVehicles',
            'accidentVehicles', 'outOfServiceVehicles', 'totalDrivers', 'activeDrivers'
        ));
    }
}
