<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetTransfer extends Model
{
    protected $fillable = [
        'asset_id', 'from_custodian_id', 'to_custodian_id', 'from_office', 'to_office', 
        'to_office_id', 'to_branch_id',
        'transfer_date', 'remarks'
    ];

    protected $casts = [
        'transfer_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (AssetTransfer $transfer) {
            // BR-01: Must update the asset custodian info when transferring
            $asset = $transfer->asset;
            
            // Validate transfer
            if ($asset && $asset->maintenance_status !== 'assigned' && $asset->maintenance_status !== 'available') {
                throw new \InvalidArgumentException("Only assigned or available assets can be transferred.");
            }

            // Find current active assignment
            $activeAssignment = AssetAssignment::where('asset_id', $transfer->asset_id)
                ->where('status', 'active')
                ->first();

            if ($activeAssignment) {
                // Mark previous assignment as returned (end the assignment)
                $activeAssignment->update([
                    'status' => 'returned',
                    'actual_return_date' => $transfer->transfer_date
                ]);
            }

            // Create new assignment for the new custodian/office
            AssetAssignment::create([
                'asset_id' => $transfer->asset_id,
                'custodian_id' => $transfer->to_custodian_id,
                'office_id' => $transfer->to_office_id,
                'branch_id' => $transfer->to_branch_id,
                'assigned_date' => $transfer->transfer_date,
                'status' => 'active'
            ]);
        });

        static::saving(function (AssetTransfer $transfer) {
            if ($transfer->to_office_id) {
                $office = Office::find($transfer->to_office_id);
                if ($office) {
                    $transfer->to_office = $office->name;
                }
            }
        });
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function fromCustodian(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_custodian_id');
    }

    public function toCustodian(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_custodian_id');
    }

    public function toOffice(): BelongsTo
    {
        return $this->belongsTo(Office::class, 'to_office_id');
    }

    public function toBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }
}
