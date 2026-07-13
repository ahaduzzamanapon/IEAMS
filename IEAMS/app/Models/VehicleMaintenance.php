<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleMaintenance extends Model
{
    protected $fillable = [
        'vehicle_id', 'maintenance_type', 'maintenance_date', 'completion_date',
        'workshop_name', 'description', 'cost', 'status', 'previous_vehicle_status'
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'completion_date' => 'date',
        'cost' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (VehicleMaintenance $m) {
            $vehicle = $m->vehicle;
            if ($vehicle) {
                // Save current vehicle status before changing
                $m->previous_vehicle_status = $vehicle->status;
                // BR-08: Vehicle under maintenance gets status 'under_maintenance'
                $vehicle->update(['status' => 'under_maintenance']);
            }
        });

        static::updated(function (VehicleMaintenance $m) {
            if ($m->isDirty('status') && $m->status === 'completed') {
                $vehicle = $m->vehicle;
                if ($vehicle) {
                    // BR-09: On completion, restore previous status or set 'available'
                    $restoreStatus = $m->previous_vehicle_status === 'under_maintenance'
                        ? 'available'
                        : ($m->previous_vehicle_status ?? 'available');
                    $vehicle->update(['status' => $restoreStatus]);
                }
            }
        });

        // BR-10: Maintenance Cost History cannot be deleted
        static::deleting(function (VehicleMaintenance $m) {
            throw new \InvalidArgumentException('BR-10: Vehicle maintenance records cannot be deleted. History must be preserved.');
        });
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
