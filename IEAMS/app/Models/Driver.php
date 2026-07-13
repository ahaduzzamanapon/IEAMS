<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    protected $fillable = [
        'name', 'father_name', 'mobile', 'nid', 'driving_license_number',
        'license_issue_date', 'license_expiry_date', 'license_category',
        'blood_group', 'permanent_address', 'present_address', 'emergency_contact', 'status'
    ];

    protected $casts = [
        'license_issue_date' => 'date',
        'license_expiry_date' => 'date',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(VehicleAssignment::class);
    }
}
