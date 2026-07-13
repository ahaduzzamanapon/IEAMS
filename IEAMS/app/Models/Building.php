<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Building extends Model
{
    protected $fillable = ['plot_id', 'name', 'number', 'footprint_area', 'total_floor', 'has_lift', 'has_parking', 'construction_status'];

    protected $casts = [
        'footprint_area' => 'decimal:2',
        'has_lift' => 'boolean',
        'has_parking' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Building $building) {
            $plot = $building->plot;
            if ($plot) {
                if ($building->footprint_area > $plot->plot_area) {
                    throw new \InvalidArgumentException("Building footprint area ({$building->footprint_area}) cannot exceed Plot area ({$plot->plot_area}).");
                }

                $buildingsQuery = Building::where('plot_id', $building->plot_id);
                if ($building->exists) {
                    $buildingsQuery->where('id', '!=', $building->id);
                }
                $totalFootprint = $buildingsQuery->sum('footprint_area');
                if (($totalFootprint + $building->footprint_area) > $plot->plot_area) {
                    throw new \InvalidArgumentException("Total footprint area of all buildings exceeds Plot Area.");
                }

                $buildingNumQuery = Building::whereHas('plot', function ($q) use ($plot) {
                    $q->where('project_id', $plot->project_id);
                })->where('number', $building->number);
                if ($building->exists) {
                    $buildingNumQuery->where('id', '!=', $building->id);
                }
                if ($buildingNumQuery->exists()) {
                    throw new \InvalidArgumentException("Building Number '{$building->number}' is already registered in this Project.");
                }
            }
        });
    }

    public function plot(): BelongsTo
    {
        return $this->belongsTo(Plot::class);
    }

    public function floors(): HasMany
    {
        return $this->hasMany(Floor::class);
    }
}
