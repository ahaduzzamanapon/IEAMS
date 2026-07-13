<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Floor extends Model
{
    protected $fillable = ['building_id', 'floor_number', 'floor_name', 'total_apartment'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Floor $floor) {
            $building = $floor->building;
            if ($building) {
                // BR-23: Floor Number must be unique within the Building
                $numQuery = Floor::where('building_id', $floor->building_id)
                    ->where('floor_number', $floor->floor_number);
                if ($floor->exists) {
                    $numQuery->where('id', '!=', $floor->id);
                }
                if ($numQuery->exists()) {
                    throw new \InvalidArgumentException("Floor Number '{$floor->floor_number}' already exists in this Building.");
                }

                // BR-25: Floor count cannot exceed Building's total floor
                $floorsQuery = Floor::where('building_id', $floor->building_id);
                if ($floor->exists) {
                    $floorsQuery->where('id', '!=', $floor->id);
                }
                if ($floorsQuery->count() >= $building->total_floor) {
                    throw new \InvalidArgumentException("Cannot add more floors. Building total floors limit ({$building->total_floor}) reached.");
                }
            }
        });
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function apartments(): HasMany
    {
        return $this->hasMany(Apartment::class);
    }
}
