<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $fillable = [
        'unique_asset_id', 'asset_type', 'category_id', 'sub_category_id',
        'brand', 'model', 'serial_number', 'quantity',
        'purchase_date', 'purchase_cost', 'capitalized_cost', 'total_cost',
        'purchase_order_number', 'invoice_number', 'vendor_id',
        'warranty_applicable', 'warranty_start_date', 'warranty_end_date',
        'depreciation_method', 'useful_life', 'salvage_value_percentage', 'salvage_value_amount', 'current_book_value',
        'maintenance_status'
    ];

    protected $casts = [
        'warranty_applicable' => 'boolean',
        'purchase_date' => 'date',
        'warranty_start_date' => 'date',
        'warranty_end_date' => 'date',
        'purchase_cost' => 'decimal:2',
        'capitalized_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'salvage_value_percentage' => 'decimal:2',
        'salvage_value_amount' => 'decimal:2',
        'current_book_value' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Asset $asset) {
            $asset->validateBusinessRules();
            $asset->calculateTotalCost();
            $asset->generateUniqueAssetId();
        });
    }

    public function validateBusinessRules()
    {
        // BR-02: If consumer asset, Brand, Model, Serial Number must be null
        if ($this->asset_type === 'consumer') {
            $this->brand = null;
            $this->model = null;
            $this->serial_number = null;
            
            // Consumer assets have no procurement details, warranty, or depreciation
            $this->purchase_date = null;
            $this->purchase_cost = null;
            $this->capitalized_cost = null;
            $this->total_cost = null;
            $this->purchase_order_number = null;
            $this->invoice_number = null;
            $this->vendor_id = null;
            $this->warranty_applicable = false;
            $this->warranty_start_date = null;
            $this->warranty_end_date = null;
            $this->depreciation_method = null;
            $this->useful_life = null;
            $this->salvage_value_percentage = null;
            $this->salvage_value_amount = null;
            $this->current_book_value = null;
        } else {
            // If fixed or current, brand, model, serial are required
            if (empty($this->brand) || empty($this->model) || empty($this->serial_number)) {
                throw new \InvalidArgumentException("Brand, Model, and Serial Number are required for Fixed/Current Assets.");
            }
            // BR-03: quantity is null for Fixed & Current (they are registered individually)
            $this->quantity = null;

            // BR-04: same serial number cannot be registered again
            $query = self::where('serial_number', $this->serial_number);
            if ($this->exists) {
                $query->where('id', '!=', $this->id);
            }
            if ($query->exists()) {
                throw new \InvalidArgumentException("Serial Number '{$this->serial_number}' is already registered.");
            }
        }

        // Warranty business rules
        if (!$this->warranty_applicable) { 
            $this->warranty_start_date = null;
            $this->warranty_end_date = null;
        } else {
            if (empty($this->warranty_start_date) || empty($this->warranty_end_date)) {
                throw new \InvalidArgumentException("Warranty start and end dates are required when warranty is applicable.");
            }
            if ($this->warranty_end_date->lt($this->warranty_start_date)) {
                throw new \InvalidArgumentException("Warranty End Date must be after Warranty Start Date.");
            }
        }

        // Depreciation BR-04: Office Supplies Category has no depreciation
        if ($this->category && $this->category->name === 'Office Supplies') {
            $this->depreciation_method = null;
            $this->useful_life = null;
            $this->salvage_value_percentage = null;
            $this->salvage_value_amount = null;
            $this->current_book_value = null;
        }
    }

    public function calculateTotalCost()
    {
        if ($this->asset_type !== 'consumer') {
            $purchase = floatval($this->purchase_cost ?? 0);
            $capitalized = floatval($this->capitalized_cost ?? 0);
            $this->total_cost = $purchase + $capitalized;
            
            // Set initial book value
            if (!$this->exists || is_null($this->current_book_value)) {
                $this->current_book_value = $this->total_cost;
            }

            // Calculate salvage amount if percentage is present
            if ($this->salvage_value_percentage) {
                $this->salvage_value_amount = $this->total_cost * ($this->salvage_value_percentage / 100);
            }
        }
    }

    public function generateUniqueAssetId()
    {
        if ($this->asset_type === 'consumer') {
            $this->unique_asset_id = null;
            return;
        }

        if (empty($this->unique_asset_id)) {
            $typeStr = strtoupper(substr($this->asset_type, 0, 3));
            $catCode = $this->category ? strtoupper($this->category->code) : 'CAT';
            $subCatCode = $this->subCategory ? strtoupper($this->subCategory->code) : 'SUB';
            $serialClean = preg_replace('/[^A-Za-z0-9]/', '', $this->serial_number ?? '0000');
            $this->unique_asset_id = "NHA-{$typeStr}-{$catCode}-{$subCatCode}-{$serialClean}";
        }
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(AssetAssignment::class);
    }

    public function transfers(): HasMany
    {
        return $this->hasMany(AssetTransfer::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(AssetMaintenance::class);
    }

    public function depreciationLogs(): HasMany
    {
        return $this->hasMany(DepreciationLog::class);
    }
}
