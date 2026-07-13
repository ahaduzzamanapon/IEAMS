<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $fillable = [
        'vehicle_number', 'registration_number', 'registration_date', 'registration_expiry_date',
        'fitness_certificate_number', 'fitness_issue_date', 'fitness_expiry_date',
        'vehicle_type', 'vehicle_category', 'vehicle_name', 'brand', 'model',
        'manufacturing_year', 'color', 'fuel_type', 'fuel_quantity',
        'chassis_number', 'engine_number', 'seating_capacity', 'status'
    ];

    protected $casts = [
        'registration_date' => 'date',
        'registration_expiry_date' => 'date',
        'fitness_issue_date' => 'date',
        'fitness_expiry_date' => 'date',
        'fuel_quantity' => 'decimal:2',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(VehicleAssignment::class);
    }
}
