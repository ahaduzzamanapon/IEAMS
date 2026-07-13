<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Vendor;
use App\Models\Project;
use App\Models\Land;
use App\Models\Plot;
use App\Models\Building;
use App\Models\Floor;
use App\Models\Apartment;
use App\Models\PropertySale;
use App\Models\Rent;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\VehicleAssignment;
use App\Models\Asset;
use App\Models\AssetAssignment;
use App\Models\AssetTransfer;
use App\Models\AssetMaintenance;
use App\Models\DepreciationLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create permissions
        $permissions = [
            ['name' => 'Manage Assets', 'slug' => 'manage-assets'],
            ['name' => 'Manage Properties', 'slug' => 'manage-properties'],
            ['name' => 'Manage Vehicles', 'slug' => 'manage-vehicles'],
            ['name' => 'Manage RBAC', 'slug' => 'manage-rbac'],
        ];

        $createdPerms = [];
        foreach ($permissions as $p) {
            $createdPerms[$p['slug']] = Permission::create($p);
        }

        // 2. Create roles
        $superAdmin = Role::create(['name' => 'Super Admin', 'slug' => 'super-admin']);
        $admin = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $officer = Role::create(['name' => 'Officer', 'slug' => 'officer']);

        // 3. Link permissions to roles
        $superAdmin->permissions()->sync(Permission::pluck('id'));
        $admin->permissions()->sync([
            $createdPerms['manage-assets']->id,
            $createdPerms['manage-properties']->id,
            $createdPerms['manage-vehicles']->id,
        ]);
        $officer->permissions()->sync([
            $createdPerms['manage-assets']->id,
        ]);

        // 4. Create default users
        $adminUser = User::create([
            'name' => 'NHA Admin',
            'email' => 'admin@nha.gov.bd',
            'password' => Hash::make('password'),
        ]);
        $adminUser->roles()->sync([$superAdmin->id]);

        $officerUser = User::create([
            'name' => 'Anisur Rahman',
            'email' => 'anis@nha.gov.bd',
            'password' => Hash::make('password'),
        ]);
        $officerUser->roles()->sync([$officer->id]);

        $user3 = User::create([
            'name' => 'Mizanur Rahman',
            'email' => 'mizan@nha.gov.bd',
            'password' => Hash::make('password'),
        ]);
        $user3->roles()->sync([$officer->id]);

        // Create default Offices and Branches
        $dho = \App\Models\Office::create(['name' => 'Dhaka Head Office', 'code' => 'DHO']);
        $cdo = \App\Models\Office::create(['name' => 'Chattogram Division Office', 'code' => 'CDO']);
        $rdo = \App\Models\Office::create(['name' => 'Rajshahi Division Office', 'code' => 'RDO']);

        $branchAdm = \App\Models\Branch::create(['office_id' => $dho->id, 'name' => 'Administration', 'code' => 'ADM']);
        $branchFin = \App\Models\Branch::create(['office_id' => $dho->id, 'name' => 'Finance & Accounts', 'code' => 'FIN']);
        $branchEst = \App\Models\Branch::create(['office_id' => $dho->id, 'name' => 'Estate Management', 'code' => 'EST']);
        $branchEng = \App\Models\Branch::create(['office_id' => $cdo->id, 'name' => 'Engineering', 'code' => 'ENG']);
        $branchOps = \App\Models\Branch::create(['office_id' => $cdo->id, 'name' => 'Operations', 'code' => 'OPS']);
        $branchPln = \App\Models\Branch::create(['office_id' => $rdo->id, 'name' => 'Regional Planning', 'code' => 'PLN']);

        // 5. Create default asset categories & subcategories
        $catFixed = Category::create(['asset_type' => 'fixed', 'name' => 'Computer Equipment', 'code' => 'COMP']);
        $catCurrent = Category::create(['asset_type' => 'current', 'name' => 'Office Furniture', 'code' => 'FURN']);
        $catConsumer = Category::create(['asset_type' => 'consumer', 'name' => 'Office Supplies', 'code' => 'OFFS']);

        $subLaptop = SubCategory::create(['category_id' => $catFixed->id, 'name' => 'Laptops', 'code' => 'LAP']);
        $subPrinter = SubCategory::create(['category_id' => $catFixed->id, 'name' => 'Printers', 'code' => 'PRN']);
        $subChair = SubCategory::create(['category_id' => $catCurrent->id, 'name' => 'Executive Chairs', 'code' => 'CHR']);
        $subPaper = SubCategory::create(['category_id' => $catConsumer->id, 'name' => 'A4 Paper packs', 'code' => 'PAP']);

        // 6. Create default vendors
        $vendor1 = Vendor::create([
            'name' => 'Walton Digitech',
            'address' => 'Chandra, Gazipur',
            'contact_person' => 'Mr. Asif Iqbal',
            'mobile' => '01711122233',
            'email' => 'asif@walton.com',
        ]);
        $vendor2 = Vendor::create([
            'name' => 'Dell Bangladesh',
            'address' => 'Gulshan 2, Dhaka',
            'contact_person' => 'Mr. David K.',
            'mobile' => '01822233344',
            'email' => 'david@dell.com',
        ]);

        // 7. Create default assets
        $asset1 = Asset::create([
            'asset_type' => 'fixed',
            'category_id' => $catFixed->id,
            'sub_category_id' => $subLaptop->id,
            'brand' => 'Dell',
            'model' => 'Latitude 5430',
            'serial_number' => 'DEL5430LAP',
            'purchase_date' => Carbon::now()->subMonths(12),
            'purchase_cost' => 120000.00,
            'capitalized_cost' => 5000.00,
            'purchase_order_number' => 'PO-2025-001',
            'invoice_number' => 'INV-9988',
            'vendor_id' => $vendor2->id,
            'warranty_applicable' => true,
            'warranty_start_date' => Carbon::now()->subMonths(12),
            'warranty_end_date' => Carbon::now()->addMonths(12),
            'depreciation_method' => 'straight-line',
            'useful_life' => 5,
            'salvage_value_percentage' => 10.00,
        ]);

        $asset2 = Asset::create([
            'asset_type' => 'fixed',
            'category_id' => $catFixed->id,
            'sub_category_id' => $subPrinter->id,
            'brand' => 'HP',
            'model' => 'LaserJet Pro',
            'serial_number' => 'HPLJPRINTER',
            'purchase_date' => Carbon::now()->subMonths(6),
            'purchase_cost' => 45000.00,
            'capitalized_cost' => 1500.00,
            'purchase_order_number' => 'PO-2025-002',
            'invoice_number' => 'INV-9989',
            'vendor_id' => $vendor1->id,
            'warranty_applicable' => true,
            'warranty_start_date' => Carbon::now()->subMonths(6),
            'warranty_end_date' => Carbon::now()->addMonths(6),
            'depreciation_method' => 'written-down-value',
            'useful_life' => 3,
            'salvage_value_percentage' => 5.00,
        ]);

        $asset3 = Asset::create([
            'asset_type' => 'consumer',
            'category_id' => $catConsumer->id,
            'sub_category_id' => $subPaper->id,
            'quantity' => 150
        ]);

        // 8. Create Asset Assignment
        AssetAssignment::create([
            'asset_id' => $asset1->id,
            'custodian_id' => $officerUser->id,
            'office_id' => $dho->id,
            'branch_id' => $branchAdm->id,
            'assigned_date' => Carbon::now()->subMonths(6),
            'status' => 'active',
        ]);

        // 9. Create Asset Maintenance
        AssetMaintenance::create([
            'asset_id' => $asset2->id,
            'maintenance_type' => 'repair',
            'maintenance_date' => Carbon::now()->subDays(10),
            'cost' => 2500.00,
            'status' => 'pending',
            'description' => 'Fuser unit replacement and roller cleaning.',
        ]);

        // 10. Create project
        $project = Project::create([
            'project_id_code' => 'NHA-PRJ-001',
            'project_code' => 'PRJ001',
            'name' => 'Uttara Model Town Phase 3 Flat Project',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'upazila' => 'Uttara',
            'mouza' => 'Mouza 12, 14',
            'total_land' => 15.50,
            'total_road_land' => 3.20,
            'estimated_project_cost' => 500000000.00,
            'total_planned_plot' => 20,
            'total_planned_apartment' => 80,
            'project_start_date' => Carbon::now()->subMonths(6),
            'expected_completion_date' => Carbon::now()->addYears(2),
            'status' => 'ongoing',
            'description' => 'Development of 80 residential apartments and plot mapping.'
        ]);

        // 11. Create Land purchase for Project
        Land::create([
            'project_id' => $project->id,
            'purchase_date' => Carbon::now()->subMonths(10),
            'purchase_value' => 120000000.00,
            'seller_information' => 'Uttara Development Agency',
            'deed_number' => 'DEED-9009-NHA',
            'registration_date' => Carbon::now()->subMonths(9),
            'khatian_number' => 'SA 445, BS 110',
            'dag_number' => 'DAG 998, 999',
            'land_amount' => 10.50,
            'land_classification' => 'Residential Flat Land',
        ]);

        // 12. Create Plots
        $plot1 = Plot::create([
            'project_id' => $project->id,
            'plot_number' => 'PLOT-01A',
            'plot_name' => 'Premium Residential Plot A',
            'plot_area' => 3600.00,
            'status' => 'vacant',
        ]);

        $plot2 = Plot::create([
            'project_id' => $project->id,
            'plot_number' => 'PLOT-02B',
            'plot_name' => 'Commercial Corner Plot B',
            'plot_area' => 5400.00,
            'status' => 'vacant',
        ]);

        // 13. Create Building & Floors
        $building = Building::create([
            'plot_id' => $plot1->id,
            'name' => 'NHA Heights Tower A',
            'number' => 'BLDG-01',
            'footprint_area' => 3000.00,
            'total_floor' => 10,
            'has_lift' => true,
            'has_parking' => true,
            'construction_status' => 'under_construction',
        ]);

        $floor1 = Floor::create([
            'building_id' => $building->id,
            'floor_number' => '1st Floor',
            'floor_name' => 'Floor 1',
            'total_apartment' => 4,
        ]);

        $floor2 = Floor::create([
            'building_id' => $building->id,
            'floor_number' => '2nd Floor',
            'floor_name' => 'Floor 2',
            'total_apartment' => 4,
        ]);

        // 14. Create Apartments
        $apt1 = Apartment::create([
            'floor_id' => $floor1->id,
            'apartment_number' => 'Apt-101',
            'name' => 'Standard Deluxe Suite 101',
            'size' => 1250.00,
            'bedrooms' => 3,
            'bathrooms' => 2,
            'balcony' => 2,
            'parking' => 'Spot 101',
            'utility_connection' => true,
            'orientation' => 'North-East',
            'status' => 'vacant',
        ]);

        $apt2 = Apartment::create([
            'floor_id' => $floor1->id,
            'apartment_number' => 'Apt-102',
            'name' => 'Standard Suite 102',
            'size' => 1100.00,
            'bedrooms' => 2,
            'bathrooms' => 2,
            'balcony' => 1,
            'parking' => 'Spot 102',
            'utility_connection' => true,
            'orientation' => 'South-West',
            'status' => 'vacant',
        ]);

        // 15. Property sales
        PropertySale::create([
            'property_type' => 'plot',
            'plot_id' => $plot2->id,
            'sale_date' => Carbon::now()->subMonths(2),
            'buyer_name' => 'Md. Farooq Hossain',
            'father_name' => 'Late Abul Kashem',
            'mother_name' => 'Begum Fatema',
            'nid' => '9988223344',
            'mobile' => '01755667788',
            'email' => 'farooq@gmail.com',
            'address' => 'House 44, Road 11, Dhanmondi, Dhaka',
            'sale_value' => 7500000.00,
            'payment_status' => 'paid',
            'handover_date' => Carbon::now()->subMonths(1),
        ]);

        // 16. Apartment Rent
        Rent::create([
            'apartment_id' => $apt2->id,
            'tenant_name' => 'Dr. Imtiaz Ahmed',
            'nid' => '112233445566',
            'mobile' => '01911223344',
            'address' => 'Mirpur 12, Dhaka',
            'occupation' => 'Professor',
            'rent_start_date' => Carbon::now()->subMonths(3),
            'rent_end_date' => Carbon::now()->addMonths(9),
            'monthly_rent' => 25000.00,
            'advance_amount' => 50000.00,
            'security_deposit' => 25000.00,
            'agreement_number' => 'AGR-NHA-2025-099',
        ]);

        // 17. Create Vehicles
        $vehicle1 = Vehicle::create([
            'vehicle_number' => 'DHAKA METRO-GHA-11-0022',
            'registration_number' => 'REG-VEH-101',
            'registration_date' => Carbon::now()->subYears(2),
            'registration_expiry_date' => Carbon::now()->addYear(),
            'fitness_certificate_number' => 'FIT-VEH-101',
            'fitness_issue_date' => Carbon::now()->subMonths(6),
            'fitness_expiry_date' => Carbon::now()->addMonths(6),
            'vehicle_type' => 'jeep',
            'vehicle_category' => 'Executive SUV',
            'vehicle_name' => 'Mitsubishi Pajero Sport',
            'brand' => 'Mitsubishi',
            'model' => 'Pajero Sport QX',
            'manufacturing_year' => 2020,
            'color' => 'Navy Blue',
            'fuel_type' => 'Diesel',
            'fuel_quantity' => 75.00,
            'chassis_number' => 'CHAS10203040',
            'engine_number' => 'ENG90807060',
            'seating_capacity' => 7,
            'status' => 'available',
        ]);

        $vehicle2 = Vehicle::create([
            'vehicle_number' => 'DHAKA METRO-GA-22-3344',
            'registration_number' => 'REG-VEH-202',
            'registration_date' => Carbon::now()->subYears(1),
            'registration_expiry_date' => Carbon::now()->addYears(2),
            'fitness_certificate_number' => 'FIT-VEH-202',
            'fitness_issue_date' => Carbon::now()->subMonths(3),
            'fitness_expiry_date' => Carbon::now()->addMonths(9),
            'vehicle_type' => 'sedan',
            'vehicle_category' => 'Office Pool Car',
            'vehicle_name' => 'Toyota Corolla',
            'brand' => 'Toyota',
            'model' => 'Corolla Altis',
            'manufacturing_year' => 2018,
            'color' => 'Silver Metallic',
            'fuel_type' => 'Octane',
            'fuel_quantity' => 50.00,
            'chassis_number' => 'CHAS20304050',
            'engine_number' => 'ENG80706050',
            'seating_capacity' => 5,
            'status' => 'available',
        ]);

        // 18. Drivers
        $driver1 = Driver::create([
            'name' => 'Abdur Rahman Kalam',
            'father_name' => 'Late Mofiz Uddin',
            'mobile' => '01511223344',
            'nid' => '334455667788',
            'driving_license_number' => 'DL-DHAKA-1122',
            'license_issue_date' => Carbon::now()->subYears(5),
            'license_expiry_date' => Carbon::now()->addYears(3),
            'license_category' => 'Heavy',
            'blood_group' => 'B+',
            'permanent_address' => 'Saturia, Manikganj',
            'present_address' => 'NHA staff quarters, Mirpur, Dhaka',
            'emergency_contact' => '01599887766',
            'status' => 'active',
        ]);

        $driver2 = Driver::create([
            'name' => 'Jasim Uddin',
            'father_name' => 'Ali Ahmed',
            'mobile' => '01855667788',
            'nid' => '445566778899',
            'driving_license_number' => 'DL-DHAKA-9988',
            'license_issue_date' => Carbon::now()->subYears(3),
            'license_expiry_date' => Carbon::now()->addYears(2),
            'license_category' => 'Light',
            'blood_group' => 'O+',
            'permanent_address' => 'Singair, Manikganj',
            'present_address' => 'Kallayanpur, Dhaka',
            'emergency_contact' => '01899001122',
            'status' => 'active',
        ]);

        // 19. Vehicle Assignment
        VehicleAssignment::create([
            'vehicle_id' => $vehicle1->id,
            'assigned_office' => 'NHA Head Office',
            'assigned_officer_id' => $adminUser->id,
            'assigned_driver_id' => $driver1->id,
            'assignment_date' => Carbon::now()->subMonths(6),
            'purpose' => 'Official transit for Chairman and Executive delegates.',
            'status' => 'active',
        ]);
    }
}
