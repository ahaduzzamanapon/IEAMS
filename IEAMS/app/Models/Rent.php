<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rent extends Model
{
    protected $fillable = [
        'apartment_id', 'tenant_name', 'nid', 'mobile', 'address', 'occupation',
        'rent_start_date', 'rent_end_date', 'monthly_rent', 'advance_amount', 'security_deposit',
        'agreement_number', 'status', 'terminated_at', 'termination_reason'
    ];

    protected $casts = [
        'rent_start_date' => 'date',
        'rent_end_date' => 'date',
        'terminated_at' => 'date',
        'monthly_rent' => 'decimal:2',
        'advance_amount' => 'decimal:2',
        'security_deposit' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Rent $rent) {
            $apt = $rent->apartment;
            // BR-51: Only Vacant apartments can be rented
            if ($apt && $apt->status !== 'vacant') {
                throw new \InvalidArgumentException("Only vacant apartments can be rented.");
            }

            // BR-55: Monthly rent must be greater than zero
            if ($rent->monthly_rent <= 0) {
                throw new \InvalidArgumentException("Monthly rent must be greater than zero.");
            }

            // BR-56: Advance amount cannot be less than monthly rent
            if ($rent->advance_amount < $rent->monthly_rent) {
                throw new \InvalidArgumentException("Advance amount cannot be less than one month's rent.");
            }
        });

        static::created(function (Rent $rent) {
            if ($rent->apartment) {
                $rent->apartment->update(['status' => 'rented']);
            }
        });

        static::updating(function (Rent $rent) {
            if ($rent->isDirty('apartment_id')) {
                $oldAptId = $rent->getOriginal('apartment_id');
                if ($oldAptId) {
                    $oldApt = Apartment::find($oldAptId);
                    if ($oldApt) {
                        $oldApt->update(['status' => 'vacant']);
                    }
                }

                $newApt = $rent->apartment;
                if ($newApt && $newApt->status !== 'vacant') {
                    throw new \InvalidArgumentException("Only vacant apartments can be rented.");
                }
            }

            // Validation checks on update
            if ($rent->monthly_rent <= 0) {
                throw new \InvalidArgumentException("Monthly rent must be greater than zero.");
            }
            if ($rent->advance_amount < $rent->monthly_rent) {
                throw new \InvalidArgumentException("Advance amount cannot be less than one month's rent.");
            }
        });

        static::updated(function (Rent $rent) {
            if ($rent->isDirty('apartment_id')) {
                if ($rent->apartment) {
                    $rent->apartment->update(['status' => 'rented']);
                }
            }
        });

        static::deleted(function (Rent $rent) {
            if ($rent->apartment) {
                $rent->apartment->update(['status' => 'vacant']);
            }
        });
    }

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class);
    }
}
