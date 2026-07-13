<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetMaintenance extends Model
{
    protected $fillable = [
        'asset_id', 'maintenance_type', 'maintenance_date', 'cost', 'status', 'description'
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'cost' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (AssetMaintenance $maintenance) {
            // Update asset status to 'under_maintenance' on creation (BR-08)
            $asset = $maintenance->asset;
            if ($asset) {
                $asset->update(['maintenance_status' => 'under_maintenance']);
            }
        });

        static::updating(function (AssetMaintenance $maintenance) {
            // When maintenance is marked completed, revert asset status to 'available' (BR-09)
            if ($maintenance->isDirty('status') && $maintenance->status === 'completed') {
                $asset = $maintenance->asset;
                if ($asset) {
                    $asset->update(['maintenance_status' => 'available']);
                }
            }
        });
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
