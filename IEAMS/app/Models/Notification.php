<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['asset_id', 'type', 'title', 'message', 'read_at'];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Check assets and create notifications for warranty expiration and salvage book values.
     */
    public static function checkAndGenerateNotifications()
    {
        $now = \Carbon\Carbon::now();
        $expiringDate = \Carbon\Carbon::now()->addDays(30);

        // 1. Check Warranty Expiry (30 days before)
        $warrantyAssets = Asset::where('warranty_applicable', true)
            ->whereNotNull('warranty_end_date')
            ->where('warranty_end_date', '<=', $expiringDate)
            ->where('warranty_end_date', '>=', $now)
            ->get();

        foreach ($warrantyAssets as $asset) {
            $exists = self::where('asset_id', $asset->id)
                ->where('type', 'warranty')
                ->exists();

            if (!$exists) {
                $daysRemaining = $now->diffInDays($asset->warranty_end_date);
                self::create([
                    'asset_id' => $asset->id,
                    'type' => 'warranty',
                    'title' => 'Warranty Expiring Soon',
                    'message' => "Warranty for asset '{$asset->unique_asset_id}' is expiring in {$daysRemaining} days (on " . \Carbon\Carbon::parse($asset->warranty_end_date)->format('d-m-Y') . ").",
                ]);
            }
        }

        // 2. Check Salvage Value Reached
        $salvageAssets = Asset::whereIn('asset_type', ['fixed', 'current'])
            ->where('maintenance_status', '!=', 'disposed')
            ->whereNotNull('salvage_value_amount')
            ->whereNotNull('current_book_value')
            ->get();

        foreach ($salvageAssets as $asset) {
            if (floatval($asset->current_book_value) <= floatval($asset->salvage_value_amount)) {
                $exists = self::where('asset_id', $asset->id)
                    ->where('type', 'salvage')
                    ->exists();

                if (!$exists) {
                    self::create([
                        'asset_id' => $asset->id,
                        'type' => 'salvage',
                        'title' => 'Asset Salvage Value Reached',
                        'message' => "Asset '{$asset->unique_asset_id}' has depreciated to its salvage value (৳" . number_format($asset->salvage_value_amount, 2) . "). It is now eligible for disposal.",
                    ]);
                }
            }
        }
    }
}
