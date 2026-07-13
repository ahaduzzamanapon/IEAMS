<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleAssignment extends Model
{
    protected $fillable = [
        'vehicle_id', 'assigned_office', 'assigned_officer_id', 'assigned_driver_id',
        'assignment_date', 'purpose', 'expected_return_date', 'actual_return_date', 'status'
    ];

    protected $casts = [
        'assignment_date' => 'date',
        'expected_return_date' => 'date',
        'actual_return_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (VehicleAssignment $assignment) {
            $vehicle = $assignment->vehicle;
            
            if ($vehicle && $vehicle->status === 'assigned') {
                throw new \InvalidArgumentException("This vehicle is already assigned and has not been returned.");
            }

            if ($vehicle && $vehicle->status === 'under_maintenance') {
                throw new \InvalidArgumentException("This vehicle is currently under maintenance and cannot be assigned.");
            }

            if ($vehicle && $vehicle->status === 'disposed') {
                throw new \InvalidArgumentException("This vehicle is disposed and cannot be assigned.");
            }

            $driver = $assignment->driver;
            if ($driver && $driver->license_expiry_date && $driver->license_expiry_date->isPast()) {
                throw new \InvalidArgumentException("The driver's license has expired. Cannot assign vehicle.");
            }

            $activeDriverAssignment = VehicleAssignment::where('assigned_driver_id', $assignment->assigned_driver_id)
                ->where('status', 'active')
                ->exists();
            if ($activeDriverAssignment) {
                throw new \InvalidArgumentException("This driver is already assigned to another active vehicle.");
            }

            if ($vehicle) {
                $vehicle->update(['status' => 'assigned']);
            }
        });
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_officer_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'assigned_driver_id');
    }
}
