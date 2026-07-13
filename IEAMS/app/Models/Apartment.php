<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Apartment extends Model
{
    protected $fillable = [
        'floor_id', 'apartment_number', 'name', 'size', 'bedrooms',
        'bathrooms', 'balcony', 'parking', 'utility_connection', 'orientation', 'status'
    ];

    protected $casts = [
        'size' => 'decimal:2',
        'utility_connection' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Apartment $apt) {
            $floor = $apt->floor;
            if ($floor) {
                // BR-28: Apartment Number must be unique within the Building & Floor
                $numQuery = Apartment::where('floor_id', $apt->floor_id)
                    ->where('apartment_number', $apt->apartment_number);
                if ($apt->exists) {
                    $numQuery->where('id', '!=', $apt->id);
                }
                if ($numQuery->exists()) {
                    throw new \InvalidArgumentException("Apartment Number '{$apt->apartment_number}' already exists on this Floor.");
                }

                // BR-29: Total Apartment count cannot exceed Floor's total_apartment
                $aptsQuery = Apartment::where('floor_id', $apt->floor_id);
                if ($apt->exists) {
                    $aptsQuery->where('id', '!=', $apt->id);
                }
                if ($aptsQuery->count() >= $floor->total_apartment) {
                    throw new \InvalidArgumentException("Cannot add more apartments. Floor total apartment limit ({$floor->total_apartment}) reached.");
                }
            }
        });
    }

    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(PropertySale::class);
    }

    public function rents(): HasMany
    {
        return $this->hasMany(Rent::class);
    }
}
