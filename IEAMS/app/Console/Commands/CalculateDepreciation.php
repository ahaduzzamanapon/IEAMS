<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asset;
use App\Models\DepreciationLog;
use Carbon\Carbon;

class CalculateDepreciation extends Command
{
    protected $signature = 'assets:calculate-depreciation';
    protected $description = 'Calculate and apply monthly asset depreciation';

    public function handle()
    {
        $this->info('Starting monthly depreciation calculation...');

        $assets = Asset::with('category')
            ->whereIn('asset_type', ['fixed', 'current'])
            ->where('maintenance_status', '!=', 'disposed')
            ->get();

        $count = 0;

        foreach ($assets as $asset) {
            /** @var \App\Models\Asset $asset */
            // BR-04: Office Supplies Category has no depreciation
            if ($asset->category && $asset->category->name === 'Office Supplies') {
                continue;
            }

            if (empty($asset->depreciation_method) || empty($asset->useful_life) || $asset->total_cost <= 0) {
                continue;
            }

            $usefulLifeMonths = $asset->useful_life * 12;
            $salvageAmount = floatval($asset->salvage_value_amount ?? 0);
            $currentBookValue = floatval($asset->current_book_value ?? $asset->total_cost);

            // BR-02: Book value cannot fall below salvage value
            if ($currentBookValue <= $salvageAmount) {
                continue;
            }

            $depreciationAmount = 0;

            if ($asset->depreciation_method === 'straight-line') {
                $depreciableBase = floatval($asset->total_cost) - $salvageAmount;
                $depreciationAmount = $depreciableBase / $usefulLifeMonths;
            } elseif ($asset->depreciation_method === 'written-down-value') {
                if ($salvageAmount > 0 && $asset->total_cost > 0) {
                    $rate = 1 - pow(($salvageAmount / $asset->total_cost), (1 / $asset->useful_life));
                } else {
                    $rate = 2.0 / $asset->useful_life;
                }
                $depreciationAmount = ($currentBookValue * $rate) / 12;
            }

            if (($currentBookValue - $depreciationAmount) < $salvageAmount) {
                $depreciationAmount = $currentBookValue - $salvageAmount;
            }

            if ($depreciationAmount <= 0) {
                continue;
            }

            $newBookValue = $currentBookValue - $depreciationAmount;

            DepreciationLog::create([
                'asset_id' => $asset->id,
                'depreciation_date' => Carbon::now(),
                'depreciation_amount' => $depreciationAmount,
                'book_value_before' => $currentBookValue,
                'book_value_after' => $newBookValue
            ]);

            $asset->current_book_value = $newBookValue;
            $asset->save();

            if (abs($newBookValue - $salvageAmount) < 0.01) {
                $this->warn("Asset {$asset->unique_asset_id} has reached its salvage value. Ready for disposal.");
                \App\Models\Notification::create([
                    'asset_id' => $asset->id,
                    'type' => 'salvage',
                    'title' => 'Asset Salvage Value Reached',
                    'message' => "Asset '{$asset->unique_asset_id}' has depreciated to its salvage value (৳" . number_format($salvageAmount, 2) . "). It is now eligible for disposal.",
                ]);
            }

            $count++;
        }

        $this->info("Completed! Depreciated {$count} assets.");
        return 0;
    }
}
