<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetAssignment extends Model
{
    protected $fillable = [
        'asset_id', 'custodian_id', 'assigned_office', 'assigned_branch',
        'office_id', 'branch_id',
        'assigned_date', 'expected_return_date', 'actual_return_date', 'status'
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'expected_return_date' => 'date',
        'actual_return_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (AssetAssignment $assignment) {
            // BR-01: Asset can only be assigned to one custodian/office at a time
            // BR-02: Assigned asset cannot be assigned elsewhere until returned
            $asset = $assignment->asset;
            if ($asset && $asset->maintenance_status === 'assigned') {
                throw new \InvalidArgumentException("This Asset is already assigned to a Custodian and has not been returned.");
            }
            if ($asset && $asset->maintenance_status === 'under_maintenance') {
                throw new \InvalidArgumentException("This Asset is currently under maintenance and cannot be assigned.");
            }

            // Update asset status
            if ($asset) {
                $asset->update(['maintenance_status' => 'assigned']);
            }
        });

        static::saving(function (AssetAssignment $assignment) {
            if ($assignment->office_id) {
                $office = Office::find($assignment->office_id);
                if ($office) {
                    $assignment->assigned_office = $office->name;
                }
            }
            if ($assignment->branch_id) {
                $branch = Branch::find($assignment->branch_id);
                if ($branch) {
                    $assignment->assigned_branch = $branch->name;
                }
            }
        });
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function custodian(): BelongsTo
    {
        return $this->belongsTo(User::class, 'custodian_id');
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
