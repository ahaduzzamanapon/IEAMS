<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Vendor;
use App\Models\Asset;
use App\Models\AssetAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssetManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $category;
    protected $subCategory;
    protected $vendor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->category = Category::create([
            'asset_type' => 'fixed',
            'name' => 'Computer Hardware',
            'code' => 'COMP'
        ]);

        $this->subCategory = SubCategory::create([
            'category_id' => $this->category->id,
            'name' => 'Notebook',
            'code' => 'NB'
        ]);

        $this->vendor = Vendor::create([
            'name' => 'Dell Supplier',
        ]);
    }

    public function test_it_creates_a_fixed_asset_with_unique_id_and_cost()
    {
        $asset = Asset::create([
            'asset_type' => 'fixed',
            'category_id' => $this->category->id,
            'sub_category_id' => $this->subCategory->id,
            'brand' => 'Dell',
            'model' => 'Latitude 5430',
            'serial_number' => 'SN123456',
            'purchase_date' => now(),
            'purchase_cost' => 1000.00,
            'capitalized_cost' => 100.00,
            'vendor_id' => $this->vendor->id,
            'warranty_applicable' => true,
            'warranty_start_date' => now(),
            'warranty_end_date' => now()->addYear(),
            'depreciation_method' => 'straight-line',
            'useful_life' => 5,
            'salvage_value_percentage' => 10.00,
        ]);

        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'brand' => 'Dell',
            'total_cost' => 1100.00,
            'unique_asset_id' => 'NHA-FIX-COMP-NB-SN123456',
        ]);
    }

    public function test_it_enforces_serial_number_uniqueness()
    {
        Asset::create([
            'asset_type' => 'fixed',
            'category_id' => $this->category->id,
            'sub_category_id' => $this->subCategory->id,
            'brand' => 'Dell',
            'model' => 'Latitude 5430',
            'serial_number' => 'SERIALUNIQUE',
            'purchase_date' => now(),
            'purchase_cost' => 1000.00,
        ]);

        $this->expectException(\InvalidArgumentException::class);

        Asset::create([
            'asset_type' => 'fixed',
            'category_id' => $this->category->id,
            'sub_category_id' => $this->subCategory->id,
            'brand' => 'HP',
            'model' => 'ProBook',
            'serial_number' => 'SERIALUNIQUE',
            'purchase_date' => now(),
            'purchase_cost' => 900.00,
        ]);
    }

    public function test_it_enforces_consumer_asset_rules_and_excludes_brand_model_serial()
    {
        $asset = Asset::create([
            'asset_type' => 'consumer',
            'category_id' => $this->category->id,
            'sub_category_id' => $this->subCategory->id,
            'brand' => 'ShouldBeNull',
            'model' => 'ShouldBeNull',
            'serial_number' => 'ShouldBeNull',
            'quantity' => 100
        ]);

        $this->assertNull($asset->brand);
        $this->assertNull($asset->model);
        $this->assertNull($asset->serial_number);
        $this->assertNull($asset->unique_asset_id);
        $this->assertEquals(100, $asset->quantity);
    }

    public function test_it_validates_active_assignment_exclusivity()
    {
        $asset = Asset::create([
            'asset_type' => 'fixed',
            'category_id' => $this->category->id,
            'sub_category_id' => $this->subCategory->id,
            'brand' => 'Dell',
            'model' => 'Latitude',
            'serial_number' => 'SNAAA',
            'purchase_date' => now(),
            'purchase_cost' => 1000.00,
        ]);

        AssetAssignment::create([
            'asset_id' => $asset->id,
            'custodian_id' => $this->user->id,
            'assigned_office' => 'Dhaka Headquarters',
            'assigned_date' => now(),
            'status' => 'active'
        ]);

        $this->expectException(\InvalidArgumentException::class);

        AssetAssignment::create([
            'asset_id' => $asset->id,
            'custodian_id' => $this->user->id,
            'assigned_office' => 'Branch Office',
            'assigned_date' => now(),
            'status' => 'active'
        ]);
    }
}
